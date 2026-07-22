# Agents in this project

This project uses a GStack-style multi-agent setup (same pattern as CloudFuze's CPQ12
tool), adapted to this project's actual stack: a PHP 8.1 fork of OpenCATS
(applicant tracking system), MySQL/Postgres, custom `.tpl` templates, plain
jQuery — no framework, no build step. Deploys run via a manually-triggered
GitHub Actions workflow — pushing to `main` never deploys by itself.

Each agent below is defined in `.claude/agents/<name>.md`. Claude Code registers
each as an invocable subagent from its YAML frontmatter (`name`, `description`).
You can invoke one directly (`Use the backend-engineer agent to ...`) or let a
workflow in `.claude/workflows/` orchestrate several in sequence.

## Roster

| Agent | Role |
|---|---|
| [architect](agents/architect.md) | Designs before code: schema (MySQL + Postgres), module/action map, request flow, ACL impact, template/JS plan. Used for new features and non-trivial fixes. |
| [backend-engineer](agents/backend-engineer.md) | Implements PHP module controllers, `src/OpenCATS/Entity/` code, DB access, schema/upgrade SQL. |
| [frontend-engineer](agents/frontend-engineer.md) | Implements `.tpl` templates, JS (`js/*.js`), and CSS. No SPA framework — split from backend because template/JS/CSS conventions differ from PHP business logic conventions. |
| [qa-engineer](agents/qa-engineer.md) | Runs/extends PHPUnit and Behat/Mink/Selenium tests; writes manual test plans for what automation doesn't cover; checks MySQL/Postgres parity. |
| [security-reviewer](agents/security-reviewer.md) | Reviews for SQLi, XSS, ACL/auth bypass, unsafe file upload handling, secret leakage. Mandatory for anything touching auth, candidate/job-order data, uploads, or the public career portal. |
| [code-reviewer](agents/code-reviewer.md) | General correctness/consistency/scope review — not security-specific, not a test-execution pass. |
| [documentation-engineer](agents/documentation-engineer.md) | Updates `CHANGELOG.MD`, `config.php.example`/`.env.example` when config changes, and sparse inline doc comments where genuinely missing. |
| [devops-engineer](agents/devops-engineer.md) | Docker build/local-dev support and maintenance of `.github/workflows/deploy.yml`. Deploy is manually triggered (`workflow_dispatch`) — devops-engineer surfaces what the pipeline doesn't cover (DB migrations, server config) and never triggers a deploy without the user's explicit deploy-gate answer. |

## Workflows

Defined in `.claude/workflows/`:

- **`new-feature.yaml`** — full pipeline: design → backend/frontend implementation
  → qa → security-review + code-review → documentation → commit gate → optional
  deploy gate.
- **`bug-fix.yaml`** — lighter pipeline: optional design (only for non-trivial
  fixes) → fix → qa → optional security-review → code-review → documentation →
  commit gate → optional deploy gate.
- **`deployment.yaml`** — the actual deploy process for this project:
  pre-deploy review → security check → deploy gate (3 questions) →
  devops-engineer confirms the code is on `main` and flags anything the
  pipeline doesn't cover → qa-engineer prepares a post-deploy smoke-test
  plan. Code deploy itself is a manually-triggered GitHub Actions workflow
  (`.github/workflows/deploy.yml`, `workflow_dispatch` only, `main`/
  production only, no staging environment) — nothing runs it without the
  deploy gate being answered first.

See [GSTACK-GUIDE.md](GSTACK-GUIDE.md) for how to decide which workflow to use,
and the root `CLAUDE.md` "Workflow Selection" section for the exact commit/deploy
gates and override phrases.

## Why this roster (vs. CPQ12's)

CPQ12 is a React/Express/MongoDB app, so its agents assume an SPA build step and
a document DB. This project is a legacy server-rendered PHP app with a real
MySQL/Postgres schema and no build tooling, so:

- `frontend-engineer` targets `.tpl`/JS/CSS files directly, not React components.
- `backend-engineer` owns raw SQL/schema concerns (including dual MySQL/Postgres
  support) that a typical Node/ORM backend agent wouldn't need to think about.
- `devops-engineer` triggers a manual GitHub Actions workflow instead of a
  fully automatic push-to-deploy pipeline, since a push-gated Environment
  approval isn't available without repo admin access.
