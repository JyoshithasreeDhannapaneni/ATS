# GStack Guide

GStack is CloudFuze's convention for using a fixed roster of specialized
Claude Code subagents plus explicit workflow pipelines, instead of one general
agent doing everything ad hoc. This project follows the same pattern as
CPQ12, adapted for a PHP/OpenCATS stack instead of React/Express/MongoDB.

## Why

- **Design before code**: `architect` produces a design doc for schema, routing,
  and ACL impact before implementation starts, so mistakes get caught on paper.
- **Separation of concerns**: backend, frontend, QA, security, and code review
  are distinct passes with distinct checklists — a single pass tends to miss
  things a checklist-driven pass catches (this codebase's own git history shows
  repeated MySQL/Postgres compatibility bugs and a 62-issue security cleanup;
  the roster exists specifically to catch that class of thing earlier).
- **Explicit gates**: nothing gets committed or deployed without the user being
  asked directly — see `CLAUDE.md` → "Workflow Selection" for the exact gate
  questions.

## How to decide: GStack workflow vs. direct edit

See `CLAUDE.md` → "Workflow Selection" for the full decision rule. Short version:

- New feature, or anything touching auth/ACL, schema, an `index.php?m=&a=`
  route, or a file upload/parsing path → use `new-feature.yaml` or
  `bug-fix.yaml`.
- Trivial cosmetic change, doc-only change, or pure investigation
  ("what does X do", "where is Y defined") → direct edit, no workflow needed.
- Say **"use gstack"** to force the full workflow on something that would
  otherwise be treated as trivial.
- Say **"quick fix"** or **"direct"** to skip the workflow even on something
  that would normally warrant it — Claude will still flag if that seems risky
  (e.g. skipping security-review on an auth change) but will follow the
  override.

## Which workflow

| Situation | Workflow |
|---|---|
| Adding new behavior (new module/action, new field, new page) | `new-feature.yaml` |
| Fixing broken/incorrect existing behavior | `bug-fix.yaml` |
| Shipping already-committed changes to production | `deployment.yaml` |

## What each phase actually produces

Workflows in `.claude/workflows/*.yaml` are read as a plan, not executed by a
YAML runner — when a workflow applies, work through its phases in order,
respecting `depends_on` and `can_run_parallel_with`, and stop hard at any phase
marked as a gate until the user answers.

- **design** (architect) → a design doc in the format specified in
  `agents/architect.md`. For `bug-fix.yaml` this phase is optional — skip it
  for single-file, single-cause fixes.
- **implementation** (backend-engineer / frontend-engineer) → actual file
  changes, plus new/updated PHPUnit tests for backend logic.
- **qa** (qa-engineer) → actual test run results (not "should pass") plus a
  manual test plan for anything automation doesn't reach, and explicit
  MySQL/Postgres coverage notes when SQL was touched.
- **security-review** (security-reviewer) → findings with concrete exploit
  scenarios, or an explicit "nothing found."
- **code-review** (code-reviewer) → blocking issues vs. suggestions.
- **documentation** (documentation-engineer) → a CHANGELOG.MD entry, and
  example-file updates if config/env vars changed.
- **commit-gate / deploy-gate** → see `CLAUDE.md` for exact wording; these are
  hard stops, not agents.

## How deploy actually works here

`.github/workflows/deploy.yml` is **manual-trigger only** (`workflow_dispatch`)
— pushing to `main` never deploys by itself. Someone with write access to
the repo must go to the Actions tab and click "Run workflow" (or Claude runs
`gh workflow run deploy.yml` when explicitly told to). Once triggered, it
SSHes into the production server and runs `git pull` + `docker compose up -d
--build` in `/opt/ATS`, with no further pause. There is no staging
environment — `main` is the only deploy target. DB migrations
(`db/upgrade-*.sql`) are **not** part of this pipeline and remain a fully
manual step.

The original design used a push-triggered workflow gated by a `production`
GitHub Environment with required-reviewer approval — that needs repo admin
access, which isn't available on this repo yet. Manual-trigger is the
substitute: it gives the same "nothing deploys without a deliberate human
action" guarantee, just via write-access permissions instead of an admin-only
feature. If admin access is granted later, switch back (see `CLAUDE.md` →
"Production deploys").

Because there's no GitHub-side pause backing this up, the deploy gate in
`CLAUDE.md` (whether/branch/environment) is the **only** thing standing
between "code is on main" and "production got the new code." Claude must
always ask all three questions before running `gh workflow run deploy.yml`
or telling the user to trigger it — never as a side effect of a push or
commit.
