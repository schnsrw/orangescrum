<p align="center">
  <img src="app/webroot/img/logo/opentask-logo.svg" alt="OpenTask" width="280">
</p>

<p align="center"><em>Powered by Orangescrum</em></p>

![GitHub commit activity](https://img.shields.io/github/commit-activity/m/orangescrum/orangescrum)
![GitHub repo size](https://img.shields.io/github/repo-size/orangescrum/orangescrum)
![GitHub issues](https://img.shields.io/github/issues/orangescrum/orangescrum)
![GitHub closed issues](https://img.shields.io/github/issues-closed/orangescrum/orangescrum)

# Free, open source Project Management software

## Introduction

OpenTask is a simple yet powerful free and open source project management tool that allows teams to organize their tasks, projects and resources with real-time collaboration. Track task progress and get notified on completion, and get the complete picture of all tasks and team activity in real time.

OpenTask is a flexible project management web application written using CakePHP, and is a community-driven fork built on top of [Orangescrum](https://www.orangescrum.com/)'s open-source codebase.

New features, enhancements, and updates are released on a regular basis.

Pull requests and bug reports are always welcome!

## Features

OpenTask provides a rich set of project management features, including:

- **Task Management** — task groups, task types, calendar/list views, due dates, task tracking
- **Time Log** — track time spent per task/project
- **Reports & Analytics** — project and resource reporting
- **Email Notifications** — task assignment, comments, reminders, digests
- **Import & Export**
- **Project Collaboration**
- **Default Status Workflow**
- **Default User Role Management**

## Screenshots

### Task List View
![TaskList](https://user-images.githubusercontent.com/104009174/164024431-7a2aa224-f01a-4a89-a04f-edfdc7a64180.png)

### Add/Edit Task Form View
![Task](https://user-images.githubusercontent.com/104009174/164024438-ba48ce20-eb87-4268-be2a-b6f3b9e64108.png)

### Task Details View
![TaskDetail](https://user-images.githubusercontent.com/104009174/164024414-8a4d6117-b200-409d-9cf4-0f3d1585a76d.png)

### Project Card View
![Project](https://user-images.githubusercontent.com/104009174/164024428-a42a6b4b-8c48-49f9-a65d-c463eb78d578.png)

### Dashboard View
![DashBoard](https://user-images.githubusercontent.com/104009174/164024434-c8821926-b57f-4f53-9136-e4da33fc6304.png)

## Quickstart with Docker

The fastest way to run OpenTask locally is with the included Docker Compose stack (PHP 7.4 + Apache + MySQL + MailHog for local email testing):

```bash
git clone https://github.com/<your-org>/opentask.git
cd opentask
docker compose up -d
```

Then open [http://localhost:8080](http://localhost:8080) in your browser. Outgoing email sent by the app is caught locally by MailHog — view it at [http://localhost:8025](http://localhost:8025).

To point the app at a real SMTP provider (Gmail, SendGrid, etc.) instead of MailHog, set `SMTP_HOST`, `SMTP_PORT`, `SMTP_UNAME`, `SMTP_PWORD`, `FROM_EMAIL`, and `SUPPORT_EMAIL` as environment variables on the `app` service in `docker-compose.yml` before starting the stack.

## Manual Installation

If you're not using Docker:

### System Requirements

- Apache with `mod_rewrite`
  - Enable curl in php.ini
  - Set `post_max_size` and `upload_max_filesize` to 200Mb in php.ini
- PHP 7.4 (this codebase does not run on PHP 8.x — it relies on `each()`, `create_function()`, and curly-brace string offsets, all removed in PHP 8.0)
- CakePHP 2.9
- MySQL 5.7 or 8.0
  - If STRICT mode is on, turn it off.

### Steps

1. Clone this repository or download and extract the archive to your working directory.
2. Grant write permission to `app/Config`, `app/tmp`, and `app/webroot` and their sub-folders (e.g. `chmod -R 0775 app/Config app/tmp app/webroot`) — this app writes to its own config and upload directories at runtime.
3. Create a new MySQL database (`utf8_unicode_ci` collation) and import `database.sql` into it.
4. Update `app/Config/database.php` with your database host/login/password/database name (all overridable via `DB_HOST`/`DB_LOGIN`/`DB_PASSWORD`/`DB_DATABASE` environment variables instead, if you prefer).
5. Configure SMTP in `app/Config/constants.php` (or via the `SMTP_*` environment variables) and run the app from your browser.

## Supported Languages

- Danish
- English
- French
- German
- Portuguese
- Spanish

## Community & Contributing

- [Report a bug](../../issues) or open a pull request — see [CONTRIBUTING.md](CONTRIBUTING.md).
- Pull requests and bug reports are always welcome!

## About

OpenTask is a community-maintained, open-source project management tool ideal for small teams or individual use — **powered by [Orangescrum](https://www.orangescrum.com/)**, whose open-source codebase this project is built on and licensed under (see [LICENSE](LICENSE)).
