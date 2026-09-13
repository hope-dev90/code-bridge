# Secrets Setup Instructions

## Required Secrets for Deploy Workflow

Add these secrets to your repository at: **Settings → Secrets and variables → Actions**

| Secret Name | Value |
|------------|-------|
| `SSH_HOST` | `197.243.23.16` |
| `SSH_PORT` | `21098` |
| `SSH_USERNAME` | `codebrig` |
| `DEPLOY_PATH` | `/home/codebrig/public_html` |
| `SSH_PRIVATE_KEY` | *Contents of ~/.ssh/github_deploy* |

## Steps to Add Secrets via Web UI

1. Go to: https://github.com/hope-dev90/code-bridge/settings/secrets/actions
2. Click **New repository secret** for each entry above
3. For `SSH_PRIVATE_KEY`:
   - Copy the entire contents of `~/.ssh/github_deploy` (including the BEGIN and END lines)
   - Paste into the secret value field
4. Click **Add secret**
5. Repeat for all 5 secrets
6. Go to **Actions** tab and re-run the Deploy workflow
