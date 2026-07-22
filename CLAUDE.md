# CLAUDE.md

## Project

CloudFuze ATS — a PHP 8.1 fork of OpenCATS (applicant tracking / recruiting
system). Server-rendered: module routing via `index.php?m=<module>&a=<action>`,
`modules/<name>/<Name>UI.php` controllers, `.tpl` templates via the custom
`lib/Template.php` engine, plain jQuery in `js/`, no build step. DB access
through `lib/DatabaseConnection.php`, with both MySQL 8.0 (primary,
`db/cats_schema.sql`) and PostgreSQL (`db/cats_schema_postgresql.sql`)
compatibility maintained. A partial PSR-4 layer exists under
`src/OpenCATS/Entity/`. Tests: PHPUnit (`src/OpenCATS/Tests/`) and Behat/Mink/
Selenium (`test/features/`).

Deployment: `.github/workflows/deploy.yml` is **manual-trigger only**
(`workflow_dispatch`) — pushing to `main` does not deploy anything by
itself. Someone with write access must go to the Actions tab and click
"Run workflow" to deploy. When run, it SSHes into the production server
(`root@<DEPLOY_HOST>:<DEPLOY_PORT>`, secrets in GitHub Actions) and runs
`git pull` + `docker compose -f docker-compose.local.yml up -d --build` in
`/opt/ATS`. Database migrations (`db/upgrade-*.sql`) are **not** part of this
pipeline and stay a manual step. (A GitHub Environment with required-reviewer
approval was the original design but requires repo admin access, which isn't
available on this repo yet — manual-trigger is the interim substitute and
achieves the same "nothing deploys without a deliberate human action"
guarantee.)

## Agents & workflows

This project uses a GStack-style multi-agent setup — see
[.claude/AGENTS_README.md](.claude/AGENTS_README.md) for the agent roster and
[.claude/GSTACK-GUIDE.md](.claude/GSTACK-GUIDE.md) for how the workflows in
[.claude/workflows/](.claude/workflows/) work.

## Workflow Selection

**Default rule**: task size and risk decide whether to run a full GStack
workflow (`new-feature.yaml` / `bug-fix.yaml`) or just make a direct edit.
Bigger blast radius and lower reversibility → more process.

### Use GStack by default for

- New features (new module/action, new page, new candidate/job-order field).
- Anything touching **auth/ACL** (`ACL.php`, `lib/ACL.php`, session handling).
- Anything touching **data models/schema** (`db/cats_schema*.sql`,
  `db/upgrade-*.sql`, `src/OpenCATS/Entity/`).
- Anything touching an **`index.php?m=&a=` route/endpoint**, especially new
  unauthenticated surfaces (career portal, OAuth callbacks).
- Anything touching **file upload/resume parsing** (`candidate-upload.php`,
  parsing utilities) — this codebase has a documented history of bugs here.
- Anything that would ship to production.

### Direct edit is fine for

- Trivial cosmetic changes (copy tweaks, a CSS color, a typo fix) with no
  security/data surface.
- Docs-only changes (README, CHANGELOG wording, comments).
- Investigation-only requests ("where is X defined," "what does Y do,"
  read-only exploration) — no code changes, no workflow needed.

### User override phrases

- **"use gstack"** — forces the full workflow even on something that would
  otherwise be treated as trivial.
- **"quick fix"** / **"direct"** — skips the workflow even where it would
  normally apply. Claude will still flag if skipping looks risky (e.g. an
  auth change with no security-review) but will follow the override once
  acknowledged.

### Commit gate (always two steps, in order)

1. Ask whether to commit.
2. If yes, ask which branch.

Never commit without both answers, regardless of which workflow (or no
workflow) produced the change.

### Deploy gate (always three steps, in order)

1. Ask whether to deploy.
2. If yes, ask which branch (this project currently has only `main`; there is
   no staging branch).
3. Ask which target environment (this project currently deploys to
   **production only** — there is no staging environment to choose between,
   but confirm explicitly rather than assuming production is meant).

### Production deploys

**This project now has CI/CD, but it is manual-trigger only**:
`.github/workflows/deploy.yml` uses `workflow_dispatch` — pushing to `main`
never deploys anything by itself. Deploying to production requires someone
with write access to go to the Actions tab and click "Run workflow." Once
triggered, it deploys via SSH (`git pull` + `docker compose up -d --build`
in `/opt/ATS`) with no further pause.

Because of that:

- Merging/pushing to `main` only requires the commit gate above — Claude
  must still never push to `main` without the user having answered both
  commit-gate questions. Pushing to `main` is not itself a deploy action.
- Claude must still ask the three deploy-gate questions above (whether to
  deploy, which branch, which environment) before triggering the workflow.
  The user's explicit "yes, deploy, main, production" answer is what
  justifies Claude running `gh workflow run deploy.yml` (or telling the
  user to click "Run workflow" themselves) — Claude must never trigger this
  workflow without that explicit answer, since there is no GitHub-side
  approval pause backing it up anymore.
- DB migrations (`db/upgrade-*.sql`) are not part of this pipeline and stay
  a fully manual step regardless of the above.
- If the repo owner later grants admin access (or sets up the `production`
  GitHub Environment with required reviewers themselves), switch
  `deploy.yml` back to triggering on push to `main` with that Environment
  as the gate — manual-trigger is a substitute for that, not the end state.
