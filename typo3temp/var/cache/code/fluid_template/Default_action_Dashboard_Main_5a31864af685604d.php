<?php
class Default_action_Dashboard_Main_5a31864af685604d extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
        return (string)'Module';
    }
    public function hasLayout() {
        return true;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
        $renderingContext->getViewHelperResolver()->addNamespaces(array (
  'core' => 
  array (
    0 => 'TYPO3\\CMS\\Core\\ViewHelpers',
  ),
  'f' => 
  array (
    0 => 'TYPO3Fluid\\Fluid\\ViewHelpers',
    1 => 'TYPO3\\CMS\\Fluid\\ViewHelpers',
  ),
  'formvh' => 
  array (
    0 => 'TYPO3\\CMS\\Form\\ViewHelpers',
  ),
));
    }
    /**
 * section Content
 */
public function section_26298499e77d870c(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
    $output0 = '';

$output0 .= '

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array2 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentDashboard.widgets'),
];

$expression3 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments1 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression3(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array2)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output4 = '';

$output4 .= '
            <div class="dashboard-grid">
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure6 = function() use ($renderingContext) {
$output7 = '';

$output7 .= '
                    <div
                        class="dashboard-item dashboard-item--w-';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widget.width')]);

$output7 .= ' dashboard-item--h-';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widget.height')]);

$output7 .= ' ';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widget.additionalCssClasses')]);

$output7 .= ' dashboard-item--enableSelect"
                        data-widget-hash="';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widgetHash')]);

$output7 .= '"
                        data-widget-key="';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widget.identifier')]);

$output7 .= '"
                    >
                        <div class="dashboard-item-content">
                            <div class="widget widget-identifier-';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widget.identifier')]);

$output7 .= '">
                                <div class="widget-waiting">
                                    ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure9 = function() use ($renderingContext) {
return NULL;
};

$arguments8 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'spinner-circle',
'size' => 'large',
];

$output7 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments8, $renderChildrenClosure9, $renderingContext);

$output7 .= '
                                </div>
                                <div class="widget-content hide"></div>
                                <div class="widget-error hide">
                                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure11 = function() use ($renderingContext) {
return NULL;
};

$arguments10 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.error',
];

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments10, $renderChildrenClosure11, $renderingContext)]);

$output7 .= '
                                </div>
                                <div class="widget-actions">
                                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array13 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentDashboard.widgetOptions.{widgetHash}.refreshAvailable'),
];

$expression14 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments12 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression14(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array13)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output15 = '';

$output15 .= '
                                        <typo3-dashboard-widget-refresh>
                                            <button type="button" class="widget-action widget-action-refresh btn btn-link">
                                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure17 = function() use ($renderingContext) {
return NULL;
};

$arguments16 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'identifier' => 'actions-refresh',
'alternativeMarkupIdentifier' => 'inline',
];

$output15 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments16, $renderChildrenClosure17, $renderingContext);

$output15 .= '
                                            </button>
                                        </typo3-dashboard-widget-refresh>
                                    ';
return $output15;
},
];

$output7 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments12, static fn() => '', $renderingContext)
;

$output7 .= '
                                    <div class="widget-action widget-action-move js-dashboard-move-widget">
                                        ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure19 = function() use ($renderingContext) {
return NULL;
};

$arguments18 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'identifier' => 'actions-move',
'alternativeMarkupIdentifier' => 'inline',
];

$output7 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments18, $renderChildrenClosure19, $renderingContext);

$output7 .= '
                                    </div>
                                    <form action="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Be\UriViewHelper
$renderChildrenClosure21 = function() use ($renderingContext) {
return NULL;
};

$array22 = [
'action' => 'removeWidget',
];

$arguments20 = [
'referenceType' => 'absolute',
'route' => 'dashboard',
'parameters' => $array22,
];

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\Be\UriViewHelper::renderStatic($arguments20, $renderChildrenClosure21, $renderingContext)]);

$output7 .= '" method="post" class="form-inline">
                                        <input type="hidden" name="widgetHash" value="';

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widgetHash')]);

$output7 .= '">
                                        <button
                                            class="widget-action widget-action-remove js-dashboard-remove-widget"
                                            data-modal-title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure24 = function() use ($renderingContext) {
return NULL;
};

$arguments23 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.remove.confirm.title',
];

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments23, $renderChildrenClosure24, $renderingContext)]);

$output7 .= '"
                                            data-modal-question="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure26 = function() use ($renderingContext) {
return NULL;
};

$arguments25 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.remove.confirm.message',
];

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments25, $renderChildrenClosure26, $renderingContext)]);

$output7 .= '"
                                            data-modal-ok="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure28 = function() use ($renderingContext) {
return NULL;
};

$arguments27 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.remove.button.ok',
];

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments27, $renderChildrenClosure28, $renderingContext)]);

$output7 .= '"
                                            data-modal-cancel="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure30 = function() use ($renderingContext) {
return NULL;
};

$arguments29 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.remove.button.close',
];

$output7 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments29, $renderChildrenClosure30, $renderingContext)]);

$output7 .= '"
                                        >
                                            ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure32 = function() use ($renderingContext) {
return NULL;
};

$arguments31 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'identifier' => 'actions-delete',
'alternativeMarkupIdentifier' => 'inline',
];

$output7 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments31, $renderChildrenClosure32, $renderingContext);

$output7 .= '
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
return $output7;
};

$arguments5 = [
'reverse' => false,
'each' => $renderingContext->getVariableProvider()->getByPath('currentDashboard.widgets'),
'as' => 'widget',
'iteration' => 'widgetIterator',
'key' => 'widgetHash',
];

$output4 .= TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments5, $renderChildrenClosure6, $renderingContext);

$output4 .= '
            </div>
        ';
return $output4;
},
'__else' => function() use ($renderingContext) {
$output33 = '';

$output33 .= '
            <div class="dashboard-empty">
                <div class="dashboard-empty-content">
                    <h2>';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure35 = function() use ($renderingContext) {
return NULL;
};

$arguments34 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:dashboard.empty.content.title',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments34, $renderChildrenClosure35, $renderingContext)]);

$output33 .= '</h2>
                    <p>';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure37 = function() use ($renderingContext) {
return NULL;
};

$arguments36 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:dashboard.empty.content.description',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments36, $renderChildrenClosure37, $renderingContext)]);

$output33 .= '</p>
                    <button
                        class="js-dashboard-addWidget btn dashboard-button hide"
                        title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure39 = function() use ($renderingContext) {
return NULL;
};

$arguments38 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments38, $renderChildrenClosure39, $renderingContext)]);

$output33 .= '"
                        data-modal-title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure41 = function() use ($renderingContext) {
return NULL;
};

$arguments40 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments40, $renderChildrenClosure41, $renderingContext)]);

$output33 .= '"
                        data-button-close-text="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure43 = function() use ($renderingContext) {
return NULL;
};

$arguments42 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add.button.close',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments42, $renderChildrenClosure43, $renderingContext)]);

$output33 .= '"
                        data-button-ok-text="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure45 = function() use ($renderingContext) {
return NULL;
};

$arguments44 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add.button.ok',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments44, $renderChildrenClosure45, $renderingContext)]);

$output33 .= '"
                    >
                        <span class="dashboard-button-icon">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure47 = function() use ($renderingContext) {
return NULL;
};

$arguments46 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'identifier' => 'actions-plus',
'alternativeMarkupIdentifier' => 'inline',
];

$output33 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments46, $renderChildrenClosure47, $renderingContext);

$output33 .= '</span>
                        <span class="dashboard-button-text">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure49 = function() use ($renderingContext) {
return NULL;
};

$arguments48 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:dashboard.empty.content.button',
];

$output33 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments48, $renderChildrenClosure49, $renderingContext)]);

$output33 .= '</span>
                    </button>
                </div>
            </div>
        ';
return $output33;
},
];

$output0 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments1, static fn() => '', $renderingContext)
;

$output0 .= '

    <div class="dashboard-add-item">
        <button
            class="js-dashboard-addWidget btn dashboard-button dashboard-button-add hide"
            title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure51 = function() use ($renderingContext) {
return NULL;
};

$arguments50 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments50, $renderChildrenClosure51, $renderingContext)]);

$output0 .= '"
            data-modal-title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure53 = function() use ($renderingContext) {
return NULL;
};

$arguments52 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments52, $renderChildrenClosure53, $renderingContext)]);

$output0 .= '"
            data-button-close-text="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure55 = function() use ($renderingContext) {
return NULL;
};

$arguments54 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add.button.close',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments54, $renderChildrenClosure55, $renderingContext)]);

$output0 .= '"
            data-button-ok-text="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure57 = function() use ($renderingContext) {
return NULL;
};

$arguments56 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add.button.ok',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments56, $renderChildrenClosure57, $renderingContext)]);

$output0 .= '"
        >
            ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure59 = function() use ($renderingContext) {
return NULL;
};

$arguments58 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'identifier' => 'actions-plus',
'alternativeMarkupIdentifier' => 'inline',
];

$output0 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments58, $renderChildrenClosure59, $renderingContext);

$output0 .= '<span class="visually-hidden">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure61 = function() use ($renderingContext) {
return NULL;
};

$arguments60 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widget.add',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments60, $renderChildrenClosure61, $renderingContext)]);

$output0 .= '</span>
        </button>
    </div>

    <template id="widgetSelector">
        <div class="dashboard-modal-tabpanel" role="tabpanel">
            <ul class="nav nav-tabs t3js-tabs" id="widget-selector" role="tablist">
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure63 = function() use ($renderingContext) {
return NULL;
};

$arguments62 = [
'name' => 'firstGroupActive',
'value' => 'false',
];
$renderChildrenClosure63 = ($arguments62['value'] !== null) ? function() use ($arguments62) { return $arguments62['value']; } : $renderChildrenClosure63;
$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::renderStatic($arguments62, $renderChildrenClosure63, $renderingContext)]);

$output0 .= '
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure65 = function() use ($renderingContext) {
$output66 = '';

$output66 .= '
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array68 = [
'0' => $renderingContext->getVariableProvider()->getByPath('groupConfiguration.widgets'),
];

$expression69 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments67 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression69(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array68)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output70 = '';

$output70 .= '
                        <li class="t3js-tabmenu-item nav-item">
                            <a
                                id="dashboard-widgetgroup-tab-';

$output70 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('groupConfiguration.identifier')]);

$output70 .= '"
                                class="nav-link ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array72 = [
'0' => $renderingContext->getVariableProvider()->getByPath('firstGroupActive'),
'1' => ' == \'false\'',
];

$expression73 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == 'false');};

$arguments71 = [
'then' => 'active',
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression73(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array72)),
    $renderingContext
),
];

$output70 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments71, static fn() => '', $renderingContext)
;

$output70 .= '"
                                data-bs-toggle="tab"
                                href="#dashboard-widgetgroup-';

$output70 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('groupConfiguration.identifier')]);

$output70 .= '"
                                role="tab"
                                aria-controls="dashboard-widgetgroup-';

$output70 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('groupConfiguration.identifier')]);

$output70 .= '"
                                aria-selected="true"
                            >';

$output70 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('groupConfiguration.title')]);

$output70 .= '</a>
                        </li>
                        ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure75 = function() use ($renderingContext) {
return NULL;
};

$arguments74 = [
'name' => 'firstGroupActive',
'value' => 'true',
];
$renderChildrenClosure75 = ($arguments74['value'] !== null) ? function() use ($arguments74) { return $arguments74['value']; } : $renderChildrenClosure75;
$output70 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::renderStatic($arguments74, $renderChildrenClosure75, $renderingContext)]);

$output70 .= '
                    ';
return $output70;
},
];

$output66 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments67, static fn() => '', $renderingContext)
;

$output66 .= '
                ';
return $output66;
};

$arguments64 = [
'key' => NULL,
'reverse' => false,
'each' => $renderingContext->getVariableProvider()->getByPath('widgetGroups'),
'as' => 'groupConfiguration',
'iteration' => 'iterator',
];

$output0 .= TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments64, $renderChildrenClosure65, $renderingContext);

$output0 .= '
            </ul>
            <div class="tab-content">
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure77 = function() use ($renderingContext) {
return NULL;
};

$arguments76 = [
'name' => 'firstGroupActive',
'value' => 'false',
];
$renderChildrenClosure77 = ($arguments76['value'] !== null) ? function() use ($arguments76) { return $arguments76['value']; } : $renderChildrenClosure77;
$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::renderStatic($arguments76, $renderChildrenClosure77, $renderingContext)]);

$output0 .= '
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure79 = function() use ($renderingContext) {
$output80 = '';

$output80 .= '
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array82 = [
'0' => $renderingContext->getVariableProvider()->getByPath('groupConfiguration.widgets'),
];

$expression83 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments81 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression83(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array82)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output84 = '';

$output84 .= '
                        <div
                            class="tab-pane ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array86 = [
'0' => $renderingContext->getVariableProvider()->getByPath('firstGroupActive'),
'1' => ' == \'false\'',
];

$expression87 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == 'false');};

$arguments85 = [
'then' => 'active',
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression87(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array86)),
    $renderingContext
),
];

$output84 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments85, static fn() => '', $renderingContext)
;

$output84 .= '"
                            id="dashboard-widgetgroup-';

$output84 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('groupConfiguration.identifier')]);

$output84 .= '"
                            role="tabpanel"
                            aria-labelledby="dashboard-widgetgroup-';

$output84 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('groupConfiguration.identifier')]);

$output84 .= '-tab"
                        >
                            <div class="dashboard-modal-items ">
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure89 = function() use ($renderingContext) {
$output90 = '';

$output90 .= '
                                    <div class="dashboard-modal-item">
                                        <button
                                            form="form-dashboard-widget-add"
                                            name="widget"
                                            value="';

$output90 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widgetIdentifier')]);

$output90 .= '"
                                            type="submit"
                                            class="dashboard-modal-item-block"
                                            >
                                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array92 = [
'0' => $renderingContext->getVariableProvider()->getByPath('widgetConfiguration.iconIdentifier'),
];

$expression93 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments91 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression93(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array92)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output94 = '';

$output94 .= '
                                                <div class="dashboard-modal-item-icon">
                                                    ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure96 = function() use ($renderingContext) {
return NULL;
};

$arguments95 = [
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'size' => 'medium',
'alternativeMarkupIdentifier' => 'inline',
'identifier' => $renderingContext->getVariableProvider()->getByPath('widgetConfiguration.iconIdentifier'),
];

$output94 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments95, $renderChildrenClosure96, $renderingContext);

$output94 .= '
                                                </div>
                                            ';
return $output94;
},
];

$output90 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments91, static fn() => '', $renderingContext)
;

$output90 .= '
                                            <div class="dashboard-modal-item-details">
                                                <div class="dashboard-modal-item-title">
                                                    ';

$output90 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widgetConfiguration.title')]);

$output90 .= '
                                                </div>
                                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array98 = [
'0' => $renderingContext->getVariableProvider()->getByPath('widgetConfiguration.description'),
];

$expression99 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments97 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression99(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array98)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output100 = '';

$output100 .= '
                                                    <div class="dashboard-modal-item-description">
                                                        ';

$output100 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('widgetConfiguration.description')]);

$output100 .= '
                                                    </div>
                                                ';
return $output100;
},
];

$output90 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments97, static fn() => '', $renderingContext)
;

$output90 .= '
                                            </div>
                                        </button>
                                    </div>
                                ';
return $output90;
};

$arguments88 = [
'reverse' => false,
'iteration' => NULL,
'each' => $renderingContext->getVariableProvider()->getByPath('groupConfiguration.widgets'),
'as' => 'widgetConfiguration',
'key' => 'widgetIdentifier',
];

$output84 .= TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments88, $renderChildrenClosure89, $renderingContext);

$output84 .= '
                            </div>
                        </div>
                        ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure102 = function() use ($renderingContext) {
return NULL;
};

$arguments101 = [
'name' => 'firstGroupActive',
'value' => 'true',
];
$renderChildrenClosure102 = ($arguments101['value'] !== null) ? function() use ($arguments101) { return $arguments101['value']; } : $renderChildrenClosure102;
$output84 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::renderStatic($arguments101, $renderChildrenClosure102, $renderingContext)]);

$output84 .= '
                    ';
return $output84;
},
];

$output80 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments81, static fn() => '', $renderingContext)
;

$output80 .= '
                ';
return $output80;
};

$arguments78 = [
'key' => NULL,
'reverse' => false,
'each' => $renderingContext->getVariableProvider()->getByPath('widgetGroups'),
'as' => 'groupConfiguration',
'iteration' => 'iterator',
];

$output0 .= TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments78, $renderChildrenClosure79, $renderingContext);

$output0 .= '
            </div>
        </div>
        <form id="form-dashboard-widget-add" action="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Be\UriViewHelper
$renderChildrenClosure104 = function() use ($renderingContext) {
return NULL;
};

$array105 = [
'action' => 'addWidget',
];

$arguments103 = [
'referenceType' => 'absolute',
'route' => 'dashboard',
'parameters' => $array105,
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\Be\UriViewHelper::renderStatic($arguments103, $renderChildrenClosure104, $renderingContext)]);

$output0 .= '" method="post" target="list_frame" class="hidden"></form>
    </template>

    <template id="dashboardModal-add">
        <form action="';

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('addDashboardUri')]);

$output0 .= '" id="formAddDashboard" class="dashboardModal-form" target="list_frame" method="post">
            <div class="form-group">
                <label class="form-label" for="dashboardModalAdd-title">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure107 = function() use ($renderingContext) {
return NULL;
};

$arguments106 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:dashboard.title',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments106, $renderChildrenClosure107, $renderingContext)]);

$output0 .= '</label>
                <input class="form-control" id="dashboardModalAdd-title" type="text" name="dashboard-title" required="required">
            </div>

            <div class="dashboard-modal-items">
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure109 = function() use ($renderingContext) {
return NULL;
};

$arguments108 = [
'name' => 'dashboardShown',
'value' => 'false',
];
$renderChildrenClosure109 = ($arguments108['value'] !== null) ? function() use ($arguments108) { return $arguments108['value']; } : $renderChildrenClosure109;
$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::renderStatic($arguments108, $renderChildrenClosure109, $renderingContext)]);

$output0 .= '
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure111 = function() use ($renderingContext) {
$output112 = '';

$output112 .= '
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array114 = [
'0' => $renderingContext->getVariableProvider()->getByPath('availableDashboardPreset.showInWizard'),
];

$expression115 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments113 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression115(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array114)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output116 = '';

$output116 .= '
                        <div class="dashboard-modal-item">
                            <input
                                type="radio"
                                name="dashboard"
                                value="';

$output116 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('availableDashboardKey')]);

$output116 .= '"
                                checked="checked"
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array118 = [
'0' => $renderingContext->getVariableProvider()->getByPath('dashboardShown'),
];

$expression119 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments117 = [
'then' => '',
'else' => 'checked="checked"',
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression119(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array118)),
    $renderingContext
),
];

$output116 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments117, static fn() => '', $renderingContext)
;

$output116 .= '
                                class="dashboard-modal-item-checkBox"
                                id="dashboardKey-';

$output116 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('availableDashboardKey')]);

$output116 .= '"
                            >
                            <label for="dashboardKey-';

$output116 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('availableDashboardKey')]);

$output116 .= '" class="dashboard-modal-item-block">
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array121 = [
'0' => $renderingContext->getVariableProvider()->getByPath('availableDashboardPreset.iconIdentifier'),
];

$expression122 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments120 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression122(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array121)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output123 = '';

$output123 .= '
                                    <span class="dashboard-modal-item-icon">
                                        ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure125 = function() use ($renderingContext) {
return NULL;
};

$arguments124 = [
'overlay' => NULL,
'state' => 'default',
'title' => NULL,
'size' => 'medium',
'alternativeMarkupIdentifier' => 'inline',
'identifier' => $renderingContext->getVariableProvider()->getByPath('availableDashboardPreset.iconIdentifier'),
];

$output123 .= TYPO3\CMS\Core\ViewHelpers\IconViewHelper::renderStatic($arguments124, $renderChildrenClosure125, $renderingContext);

$output123 .= '
                                    </span>
                                ';
return $output123;
},
];

$output116 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments120, static fn() => '', $renderingContext)
;

$output116 .= '
                                <span class="dashboard-modal-item-details">
                                    <span class="dashboard-modal-item-title">
                                        ';

$output116 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('availableDashboardPreset.title')]);

$output116 .= '
                                    </span>
                                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array127 = [
'0' => $renderingContext->getVariableProvider()->getByPath('availableDashboardPreset.description'),
];

$expression128 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments126 = [
'then' => NULL,
'else' => NULL,
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression128(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array127)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output129 = '';

$output129 .= '
                                        <span class="dashboard-modal-item-description">
                                                ';

$output129 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('availableDashboardPreset.description')]);

$output129 .= '
                                        </span>
                                    ';
return $output129;
},
];

$output116 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments126, static fn() => '', $renderingContext)
;

$output116 .= '
                                </span>
                            </label>
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure131 = function() use ($renderingContext) {
return NULL;
};

$arguments130 = [
'name' => 'dashboardShown',
'value' => 'true',
];
$renderChildrenClosure131 = ($arguments130['value'] !== null) ? function() use ($arguments130) { return $arguments130['value']; } : $renderChildrenClosure131;
$output116 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::renderStatic($arguments130, $renderChildrenClosure131, $renderingContext)]);

$output116 .= '
                        </div>
                    ';
return $output116;
},
];

$output112 .= TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::renderStatic($arguments113, static fn() => '', $renderingContext)
;

$output112 .= '
                ';
return $output112;
};

$arguments110 = [
'reverse' => false,
'iteration' => NULL,
'each' => $renderingContext->getVariableProvider()->getByPath('dashboardPresets'),
'as' => 'availableDashboardPreset',
'key' => 'availableDashboardKey',
];

$output0 .= TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments110, $renderChildrenClosure111, $renderingContext);

$output0 .= '
            </div>
        </form>
    </template>

    <template id="dashboardModal-configure">
        <form action="';

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('configureDashboardUri')]);

$output0 .= '" id="configureDashboardForm" class="dashboardModal-form" target="list_frame" method="post">
            <input type="hidden" name="currentDashboard" value="';

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentDashboard.identifier')]);

$output0 .= '">
            <div class="form-group">
                <label class="form-label" for="dashboardModalConfigure-title">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure133 = function() use ($renderingContext) {
return NULL;
};

$arguments132 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'alternativeLanguageKeys' => NULL,
'key' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:dashboard.title',
];

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::renderStatic($arguments132, $renderChildrenClosure133, $renderingContext)]);

$output0 .= '</label>
                <input class="form-control" id="dashboardModalConfigure-title" type="text" name="dashboard[title]" value="';

$output0 .= call_user_func_array( function ($var) { return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentDashboard.title')]);

$output0 .= '" required="required">
            </div>
        </form>
    </template>
';

    return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
    $output134 = '';

$output134 .= '

';

$output134 .= '';

$output134 .= '

';

$output134 .= '';

$output134 .= '


';

    return $output134;
}

}

#