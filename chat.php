<?php
/**
 * CodeBridge AI assistant — database-first public endpoint.
 * Gemini credentials stay in /home/codebrig/.env (never in frontend JS).
 * Live counts come from allowlisted queries in cb-data.php.
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method', 'reply' => 'Method not allowed.']);
    exit;
}

require __DIR__ . '/cb-data.php';

function loadEnv($path) {
    if (!is_file($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if ($key === '') {
            continue;
        }
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

loadEnv('/home/codebrig/.env');
if (!getenv('AI_API_KEY') && !getenv('GEMINI_API_KEY')) {
    loadEnv(__DIR__ . '/.env');
}

$apiKey = getenv('AI_API_KEY') ?: getenv('GEMINI_API_KEY');
$model  = getenv('GEMINI_MODEL') ?: 'gemini-3.6-flash';

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput ?: '[]', true);
if (!is_array($input)) {
    $input = [];
}

$userMessage = trim((string)($input['message'] ?? ''));
$langCode = strtoupper((string)($input['lang'] ?? 'EN'));
if (!in_array($langCode, ['EN', 'FR', 'KINY'], true)) {
    $langCode = 'EN';
}
$history = $input['history'] ?? [];
if (!is_array($history)) {
    $history = [];
}

$ui = [
    'EN' => [
        'empty' => 'Please type a message.',
        'long' => 'That message is too long. Please shorten it.',
        'network' => 'The assistant is temporarily unavailable. Please try again in a moment, or contact us at info@codebrige.rw / +250 788 288 546.',
        'unknown' => 'I could not find that in the available CodeBridge data. Ask about our services, projects, news, partners, or contact details — or write to info@codebrige.rw.',
    ],
    'FR' => [
        'empty' => 'Veuillez saisir un message.',
        'long' => 'Ce message est trop long. Merci de le raccourcir.',
        'network' => 'L’assistant est temporairement indisponible. Réessayez dans un instant, ou contactez-nous à info@codebrige.rw / +250 788 288 546.',
        'unknown' => 'Je n’ai pas trouvé cela dans les données CodeBridge disponibles. Posez une question sur nos services, projets, actualités, partenaires ou contacts — ou écrivez à info@codebrige.rw.',
    ],
    'KINY' => [
        'empty' => 'Andika ubutumwa.',
        'long' => 'Ubutumwa buracyaye cyane. Ongera ubugabanye.',
        'network' => 'Umufasha ntakora neza ubu. Ongera ugerageze hanyuma, cyangwa twohereze ku info@codebrige.rw / +250 788 288 546.',
        'unknown' => 'Sinabona ayo makuru mu makuru ya CodeBridge aboneka. Baza serivisi, imishinga, amakuru, abafatanyabikorwa, cyangwa aho twandikira — cyangwa twohereze ku info@codebrige.rw.',
    ],
];
$msg = $ui[$langCode];

if ($userMessage === '') {
    echo json_encode(['ok' => false, 'error' => 'empty', 'reply' => $msg['empty']]);
    exit;
}
if (mb_strlen($userMessage) > 2000) {
    echo json_encode(['ok' => false, 'error' => 'too_long', 'reply' => $msg['long']]);
    exit;
}

function clean_reply(string $text): string
{
    $text = str_replace(["\r\n", "\r"], "\n", trim($text));
    if ($text === '') {
        return $text;
    }
    $text = preg_replace('/\*\*(.+?)\*\*/us', '$1', $text) ?? $text;
    $text = preg_replace('/__(.+?)__/us', '$1', $text) ?? $text;
    $text = preg_replace('/`([^`]+)`/u', '$1', $text) ?? $text;
    $text = preg_replace('/^[ \t]*#{1,6}[ \t]+/um', '', $text) ?? $text;
    $text = preg_replace('/^[ \t]*-{2,}[ \t]*$/um', '', $text) ?? $text;
    $text = preg_replace('/^[ \t]*(?:--+|[-–—*•])[ \t]+/um', '', $text) ?? $text;
    $text = preg_replace('/^[ \t]*--[ \t]*/um', '', $text) ?? $text;
    $text = preg_replace('/[ \t]+--[ \t]+/u', '; ', $text) ?? $text;
    $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;
    return trim($text);
}

$admin = cb_is_admin();

$smalltalk = cb_smalltalk_reply($userMessage, $langCode);
if ($smalltalk !== null) {
    echo json_encode(['ok' => true, 'reply' => $smalltalk, 'source' => 'conversation']);
    exit;
}

$snap = cb_live_snapshot($admin);

if (cb_is_off_topic($userMessage)) {
    echo json_encode(['ok' => true, 'reply' => cb_off_topic_reply(), 'source' => 'scope']);
    exit;
}

if (cb_is_restricted_ask($userMessage, $admin)) {
    echo json_encode(['ok' => true, 'reply' => cb_permission_reply(), 'source' => 'auth']);
    exit;
}

$direct = cb_direct_stats_reply($userMessage, $langCode, $snap);
if ($direct !== null) {
    echo json_encode(['ok' => true, 'reply' => clean_reply($direct), 'source' => 'database']);
    exit;
}

$langHint = [
    'EN' => 'Reply in English.',
    'FR' => 'Réponds en français.',
    'KINY' => 'Subiza mu Kinyarwanda.',
][$langCode];
$adminLabel = $admin
    ? 'a logged-in administrator (application counts without personal data are allowed)'
    : 'a public visitor (no internal application data)';

$system = <<<TXT
You are the official CodeBridge AI assistant (also written Codebridge / Codebrige). You are NOT a general-purpose AI assistant.

Simple conversation is allowed without inventing facts: greetings (hello, hi, good morning), how are you, thanks, goodbye, who are you, and what can you do. Answer those warmly and briefly, then offer to help with CodeBridge. Do not query or invent statistics for those messages.

Scope for everything else: CodeBridge, its website (https://codebrige.rw), application, services, products, projects, team, partners, public users/visitors/analytics when present in the data below, news, and official information.

If the user asks a substantive question unrelated to CodeBridge, do not answer it. Reply with:
I'm the CodeBridge AI assistant, so I mainly help with CodeBridge and its platform. What would you like to know about CodeBridge?

Database first:
1. The LIVE DATABASE SNAPSHOT below was queried just now. Use it as the source of truth for counts, news titles, jobs, gallery, and frontend users.
2. Never guess, estimate, or invent a statistic. If a metric is listed as not in the database, say it is unavailable.
3. Never claim you queried the database for a number that is not in the snapshot.
4. Distinguish unique visitors, total visits, sessions, page views, registered users, and active users. They are not the same. None of the visitor metrics are stored today.
5. When you use a time period, name it clearly (today, this month, this year, all time, etc.).
6. Label website content as website content, not as a database value.
7. Label a number taken from the snapshot as a database value.
8. If you calculate a percentage or growth from snapshot numbers, say it is calculated.

Permissions:
This visitor is {$adminLabel}.
If a topic is restricted, reply exactly: You don't have permission to access that information.
Never expose passwords, hashes, API keys, tokens, database credentials, personal emails/phones from applications, payment data, or other secrets. The snapshot already excludes those.

Writing rules: plain sentences only. No markdown, bold asterisks, headings, or code fences. Never start a line with -, --, *, or •. If several points are needed, number them as 1) 2) 3) or put them in one paragraph.

{$langHint}

LIVE DATA AND OFFICIAL FACTS:
TXT;
$system .= "\n" . cb_snapshot_text($snap);

function kb_match($text, $lang) {
    $q = mb_strtolower($text);
    $isFr = ($lang === 'FR') || preg_match('/\b(quoi|comment|bonjour|s\'?il vous plaît|veuillez|cybersécurité|stratégie|contactez-nous)\b/u', $q);
    $isRw = ($lang === 'KINY') || preg_match('/\b(ese|ni iki|serivisi|twandikire|amahugurwa|umushinga|muraho)\b/u', $q);

    $pack = function ($en, $fr, $rw) use ($isFr, $isRw) {
        if ($isRw) {
            return $rw;
        }
        if ($isFr) {
            return $fr;
        }
        return $en;
    };

    $contact = $pack(
        "You can reach Codebridge at info@codebrige.rw or +250 788 288 546. We are in Kigali — Gasabo, Kabuga (Rusoro), 26G9+6H9. Hours: Mon–Fri 8:00 AM–5:00 PM; Sat & Sun 12:00–5:00 PM.",
        "Contactez Codebridge à info@codebrige.rw ou au +250 788 288 546. Nous sommes à Kigali — Gasabo, Kabuga (Rusoro), 26G9+6H9. Horaires : lun–ven 8h–17h ; sam & dim 12h–17h.",
        "Mushobora kugera kuri Codebridge kuri info@codebrige.rw cyangwa +250 788 288 546. Turi i Kigali — Gasabo, Kabuga (Rusoro), 26G9+6H9. Amasaha: Kuwa mbere–Kuwa gatanu 8h–17h; Kuwa gatandatu n'icyumweru 12h–17h."
    );

    if (preg_match('/contact|phone|email|address|where are you|twandik|telefon|adresse|où êtes/u', $q)) {
        return $contact;
    }
    if (preg_match('/what is codebridge|what is code.?bridge|qui est codebridge|codebridge ni iki|about codebridge/u', $q)) {
        return $pack(
            "Codebridge is a Kigali technology company. We help organisations succeed with information technology and security — software, cybersecurity, AI, consultancy, internships, and IKiraro education programmes.",
            "Codebridge est une entreprise technologique de Kigali. Nous aidons les organisations grâce à l’informatique et à la sécurité : logiciels, cybersécurité, IA, conseil, stages et programmes éducatifs IKiraro.",
            "Codebridge ni isosiyete ya tekinoroji i Kigali. Dufasha ibigo gutsinda binyuze mu ikoranabuhanga n'umutekano — porogaramu, umutekano wa mudasobwa, AI, inama, amahugurwa n'amashuri ya IKiraro."
        );
    }
    if (preg_match('/internship|stage|amahugurwa|tvet|university student/u', $q)) {
        return $pack(
            "Codebridge offers an Internship Program for TVET and university students interested in ICT. Interns get practical experience, mentorship, exposure to real technology projects, and industry-relevant skills. See the Services page or Careers → Internship programs, or contact info@codebrige.rw.",
            "Codebridge propose un programme de stage pour les étudiants TVET et universitaires intéressés par les TIC : expérience pratique, mentorat, projets réels et compétences utiles en entreprise. Voir Services ou Carrières, ou info@codebrige.rw.",
            "Codebridge itanga amahugurwa ku banyeshuri ba TVET n'amakuru y'amashuri makuru bashishikajwe n'ICT. Babona uburambe, ubujyanama, imishinga nyayo n'ubumenyi bukenewe ku kazi. Reba Serivisi cyangwa Akazi, cyangwa info@codebrige.rw."
        );
    }
    if (preg_match('/ikiraro.*e-?learn|e-learning|elearrning|platforme/u', $q)) {
        return $pack(
            "IKiraro E-Learning is Codebridge’s digital learning platform for practical skills in programming, robotics, AI, cybersecurity, data science, cloud, UI/UX, entrepreneurship and emerging technologies. Learners get interactive courses, projects, mentorship, certifications and innovation challenges. Open it at https://ikiraro-elearrning.vercel.app/",
            "IKiraro E-Learning est la plateforme d’apprentissage de Codebridge : programmation, robotique, IA, cybersécurité, data science, cloud, UI/UX, entrepreneuriat et technologies émergentes, avec cours interactifs, projets, mentorat, certifications et défis. https://ikiraro-elearrning.vercel.app/",
            "IKiraro E-Learning ni urubuga rwa Codebridge rwo kwiga porogaramu, robotike, AI, umutekano wa mudasobwa, data science, cloud, UI/UX, ubucuruzi n'ikoranabuhanga rishya. Harimo amasomo, imishinga, ubujyanama, impamyabumenyi n'amarushanwa. https://ikiraro-elearrning.vercel.app/"
        );
    }
    if (preg_match('/ikiraro|innovation hub/u', $q)) {
        return $pack(
            "IKiraro Innovation Hub is a Codebridge service for primary and secondary schools. It equips learners aged 8–15 and above with practical skills in robotics and coding, cybersecurity, web development, mobile apps, and AI — with emphasis on creativity, problem-solving and innovation.",
            "IKiraro Innovation Hub est un service Codebridge pour les écoles primaires et secondaires. Il forme les jeunes de 8–15 ans et plus à la robotique et au code, à la cybersécurité, au web, aux apps mobiles et à l’IA, en stimulant créativité, résolution de problèmes et innovation.",
            "IKiraro Innovation Hub ni serivisi ya Codebridge ku mashuri abanza n'ayisumbuye. Yigisha abanyeshuri bafite imyaka 8–15 no hejuru robotike n'iyandika, umutekano wa mudasobwa, web, porogaramu za telefoni na AI — bishimangira ubuhanga n'ugukemura ibibazo."
        );
    }
    if (preg_match('/cyber|umutekano wa mudasobwa|cybersécurité/u', $q) && preg_match('/what|n\'?est|ni iki|service/u', $q)) {
        return $pack(
            "Cybersecurity is the practice of protecting systems, networks and data from attack or misuse. At Codebridge it is a core service, and we also teach cybersecurity through IKiraro programmes.",
            "La cybersécurité consiste à protéger les systèmes, réseaux et données. Chez Codebridge c’est un service central, également enseigné dans les programmes IKiraro.",
            "Umutekano wa mudasobwa ni ukurinda sisitemu, rezo n'amakuru. Ni serivisi y'ingenzi ya Codebridge, kandi yigishwa na gahunda za IKiraro."
        );
    }
    if (preg_match('/\bai\b|machine learning|ubwenge bwikoranabuhanga|intelligence artificielle/u', $q) && preg_match('/what|n\'?est|ni iki|service|offer/u', $q)) {
        return $pack(
            "AI (artificial intelligence) is software that learns from data to support decisions or automate tasks. Codebridge offers AI & Machine Learning as a core service, plus AI training in IKiraro programmes. I cannot invent unpublished product details.",
            "L’IA est un logiciel qui apprend à partir des données. Codebridge propose l’IA et le machine learning comme service, et forme aussi à l’IA via IKiraro. Je n’invente pas de détails produits non publiés.",
            "AI ni porogaramu yiga ku makuru. Codebridge itanga serivisi ya AI & Machine Learning, kandi IKiraro yigisha AI. Sinshobora kuvuga ibicuruzwa bitaratangazwa."
        );
    }
    if (preg_match('/consult|strategy|stratégie|inama/u', $q)) {
        return $pack(
            "Consultancy & Strategy is a core Codebridge service: we help organisations plan and deliver digital transformation with practical technology choices.",
            "Conseil & stratégie est un service central de Codebridge : nous aidons les organisations à planifier et réaliser leur transformation numérique.",
            "Inama n'ingamba ni serivisi y'ingenzi ya Codebridge: dufasha ibigo gutegura no gushyira mu bikorwa ihinduka rya tekinoroji."
        );
    }
    if (preg_match('/partner|partenaire|abafatanya/u', $q)) {
        return $pack(
            "Codebridge collaborates with Core Group, Loxotech, Le Plaisir d’Enfant School, Saint Emmanuel School Complex, Codible Group, Karenge Adventist Secondary School, and IKiraro Innovation Hub. Partner websites are linked from the Partners section.",
            "Codebridge collabore avec Core Group, Loxotech, l’école Le Plaisir d’Enfant, Saint Emmanuel School Complex, Codible Group, Karenge Adventist Secondary School et IKiraro Innovation Hub. Les sites officiels sont dans la section Partenaires.",
            "Codebridge ikorana na Core Group, Loxotech, ishuri Le Plaisir d’Enfant, Saint Emmanuel School Complex, Codible Group, Karenge Adventist Secondary School na IKiraro Innovation Hub. Imirongo iri mu gice cy'abafatanyabikorwa."
        );
    }
    if (preg_match('/project|projet|imishinga/u', $q)) {
        return $pack(
            "The website presents Codebridge work across web apps, mobile apps, AI and cybersecurity-related solutions — including school and HR systems and IKiraro education platforms. For a live engagement, contact info@codebrige.rw.",
            "Le site présente des réalisations web, mobile, IA et cybersécurité, dont des systèmes scolaires/RH et les plateformes IKiraro. Pour un projet, écrivez à info@codebrige.rw.",
            "Urubuga rwereakana imishinga ya web, mobile, AI n'umutekano wa mudasobwa, harimo sisitemu z'amashuri/HR na IKiraro. Ku mushinga, twohereze ku info@codebrige.rw."
        );
    }
    if (preg_match('/services?|serivisi|offre/u', $q)) {
        return $pack(
            "Codebridge’s highlighted services are Cybersecurity, AI & Machine Learning, Consultancy & Strategy, the Internship Program, IKiraro Innovation Hub, and the IKiraro E-Learning Platform (https://ikiraro-elearrning.vercel.app/). The Services page also covers software, web/mobile, e-government integration and safety services.",
            "Les services mis en avant sont : cybersécurité, IA & machine learning, conseil & stratégie, programme de stage, IKiraro Innovation Hub et la plateforme IKiraro E-Learning (https://ikiraro-elearrning.vercel.app/). La page Services couvre aussi logiciels, web/mobile, intégration e-gouvernement et sécurité incendie/vidéo.",
            "Serivisi z'ingenzi za Codebridge ni umutekano wa mudasobwa, AI & Machine Learning, inama n'ingamba, amahugurwa, IKiraro Innovation Hub n'urubuga IKiraro E-Learning (https://ikiraro-elearrning.vercel.app/). Hari kandi porogaramu, web/mobile, guhuza serivisi za Leta n'umutekano."
        );
    }
    return null;
}

function gemini_chat($apiKey, $model, $system, $userMessage, $history) {
    $contents = [];
    $count = 0;
    foreach ($history as $turn) {
        if ($count >= 8 || !is_array($turn)) {
            continue;
        }
        $role = (($turn['role'] ?? '') === 'bot' || ($turn['role'] ?? '') === 'model') ? 'model' : 'user';
        $text = trim((string)($turn['text'] ?? $turn['content'] ?? ''));
        if ($text === '') {
            continue;
        }
        $contents[] = ['role' => $role, 'parts' => [['text' => mb_substr($text, 0, 1500)]]];
        $count++;
    }
    $contents[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

    $payload = [
        'systemInstruction' => [
            'parts' => [['text' => $system]],
        ],
        'contents' => $contents,
        'generationConfig' => [
            'temperature' => 0.15,
            'maxOutputTokens' => 700,
        ],
    ];

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode($model) . ':generateContent?key=' . rawurlencode($apiKey);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlError || $httpCode !== 200) {
        return [null, $httpCode ?: 0, $curlError ?: $response];
    }
    $data = json_decode($response, true);
    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
    return [$reply ? clean_reply($reply) : null, $httpCode, null];
}

if ($apiKey && $apiKey !== 'your_actual_key_here') {
    $models = [];
    foreach ([$model, 'gemini-3.6-flash', 'gemini-flash-latest'] as $candidate) {
        if ($candidate && !in_array($candidate, $models, true)) {
            $models[] = $candidate;
        }
    }
    foreach ($models as $tryModel) {
        [$reply, $code, $detail] = gemini_chat($apiKey, $tryModel, $system, $userMessage, $history);
        if ($reply) {
            echo json_encode(['ok' => true, 'reply' => clean_reply($reply), 'source' => 'gemini']);
            exit;
        }
        $detailText = $detail ? (' — ' . mb_substr((string)$detail, 0, 400)) : '';
        error_log('chat.php Gemini unavailable model=' . $tryModel . ' HTTP ' . $code . $detailText);
    }
}

$kb = kb_match($userMessage, $langCode);
if ($kb) {
    echo json_encode(['ok' => true, 'reply' => clean_reply($kb), 'source' => 'knowledge']);
    exit;
}

echo json_encode(['ok' => false, 'error' => 'unavailable', 'reply' => $msg['network']]);
