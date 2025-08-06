
# Librebooking

[![GitHub issues](https://img.shields.io/github/issues/LibreBooking/app)](https://github.com/LibreBooking/app/issues)
[![Last commit](https://img.shields.io/github/last-commit/LibreBooking/app)](https://github.com/LibreBooking/app/commits)
[![GitHub release](https://img.shields.io/github/v/release/LibreBooking/app?include_prereleases)](https://github.com/LibreBooking/app/releases)
[![License: GPL v3](https://img.shields.io/badge/license-GPLv3-blue.svg)](https://github.com/LibreBooking/app/blob/develop/LICENSE.md)

[![GitHub stars](https://img.shields.io/github/stars/LibreBooking/app?style=flat)](https://github.com/LibreBooking/app/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/LibreBooking/app?style=flat)](https://github.com/LibreBooking/app/network)

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-brightgreen.svg?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.5%2B-blue.svg?logo=mysql)](https://www.mysql.com/)
![Platform](https://img.shields.io/badge/Platform-Web-lightgrey)
![Status](https://img.shields.io/badge/Status-Active-green)

[![Docker](https://img.shields.io/badge/Docker-Supported-blue?logo=docker)](https://github.com/LibreBooking/docker)
[![Docker pulls](https://img.shields.io/docker/pulls/librebooking/librebooking)](https://github.com/LibreBooking/docker)

[![Discord](https://img.shields.io/badge/Discord-5865F2?style=flat&logo=discord&logoColor=white)](https://discord.gg/4TGThPtmX8)
[![Wiki](https://img.shields.io/badge/Wiki-Available-lightgrey?logo=read-the-docs)](https://github.com/LibreBooking/app/wiki)

⭐ Star us on GitHub — it motivates us a lot!

🔥 Join the community: [Discord discussion channel](https://discord.gg/4TGThPtmX8) .

## Table of Contents

- [About](#-about)
- [Features](#-features)
- [Demo](#-demo)
- [Screenshots](#-screenshots)
- [Installation & Deployment](#-installation--deployment)
- [Developer Documentation](#-developer-documentation)
- [Configuration & Theming](#-configuration--theming)
- [ReCaptcha](#-recaptcha)
- [Community & Support](#-community--support)
- [Contributing](#-contributing)
- [Roadmap](#-roadmap)
- [License](#-license)

## 🚀 About

**LibreBooking** is an open-source resource scheduling solution. It provides a
flexible, mobile-friendly, and extensible interface for organizations to manage
resource reservations.

The repository for LibreBooking is hosted on GitHub at
<https://github.com/LibreBooking/app>; the `develop` branch contains the latest
code.

LibreBooking is a fork of Booked Scheduler, based on Booked Scheduler's last
open-source version released in 2020. Since then, LibreBooking has evolved
significantly and diverged from the original project.

## ✨ Features

- [x] Multi-resource booking & waitlists
- [x] DataTables for advanced listings
- [x] Role-based access control
- [x] Quotas and credits for reservations
- [x] Granular usage reporting
- [x] Responsive Bootstrap 5 interface
- [x] Custom themes and color schemes
- [x] Plugin-ready architecture
- [x] Outlook/Thunderbird integration through ics

## 🧪 Demo

A live demo instance of LibreBooking is available for testing:

[Try the demo](https://librebooking-demo.fly.dev/)

| Role  | Username | Password    |
| ----- | -------- | ----------- |
| Admin | `admin`  | `demoadmin` |
| User  | `user`   | `demouser`  |

Note: This instance is public and **resets every 20 minutes** to ensure a clean environment. Startup might take a few seconds, so please be patient.

## 📸 Screenshots

![Login](./Web/img/readme/02.png)
![Schedules](./Web/img/readme/06.png)
![Dashboard](./Web/img/readme/03.png)
![User profile](./Web/img/readme/04.png)
![Search](./Web/img/readme/07.png)
![DataTables example](./Web/img/readme/15.png)

## 🔧 Installation & Deployment

### Manual Installation

To run LibreBooking from a prebuilt release, your server needs:

- PHP >= 8.2 with the  extensions: pdo, mbstring, openssl, tokenizer, json, curl, xml, ctype, bcmath, fileinfo
- A web server like Apache or Nginx
- MySQL >= 5.5
- Composer (for managing PHP dependencies)
- Git (optional, useful for cloning the repository or managing updates)

For full setup instructions, see
[INSTALLATION](https://github.com/LibreBooking/app/blob/develop/docs/source/INSTALLATION.rst)

### Docker Deployment

LibreBooking is available as a Docker container. See [LibreBooking Docker README](https://github.com/LibreBooking/docker) for complete setup.

```bash
git clone https://github.com/LibreBooking/docker.git
cd docker
docker-compose up -d
```

## 💻 Developer Documentation

- See
  [docs/source/README.md](https://github.com/LibreBooking/app/blob/develop/docs/source/DEVELOPER-README.rst
)
  for developer notes.
- See [docs/source/API.md](https://github.com/LibreBooking/app/blob/develop/docs/source/API.rst)
  for API notes.
- See
  [docs/source/Oauth2-Configuration.md](https://github.com/LibreBooking/app/blob/develop/docs/source/Oauth2-Configuration.rst)
  for Oauth2 configuration.
- See
  [docs/source/SAML-Configuration.md](https://github.com/LibreBooking/app/blob/develop/docs/source/SAML-Configuration.rst)
  for SAML configuration.
- Codebase follows PSR-12 standards and GitHub Flow.

## 🎨 Configuration & Theming

For configuration options, see the
[Configuration Guide](https://github.com/LibreBooking/app/blob/develop/docs/source/CONFIGURATION.rst).

Recent configuration highlights:

- Change theme via `config.php`:

  ```php
  'css.theme' = 'default';
  ```

- Theme options: 'default', 'dimgray', 'dark_red', 'dark_green', 'french_blue', 'orange'
- Customize `Web/css/librebooking.css`

## 🔒 ReCaptcha

As of 09-Mar-2023, ReCaptcha integration updated to v3. Generate new keys for your domain if using ReCaptcha.

## 💬 Community & Support

- [Discord](https://discord.gg/4TGThPtmX8)
- [Wiki](https://github.com/LibreBooking/app/wiki)
- [Issues](https://github.com/LibreBooking/app/issues)
- [Discussions](https://github.com/LibreBooking/app/discussions)

## 🤝 Contributing

- Fork, file issues, suggest improvements.
- Even non-coders can help by reporting bugs, testing, updating issues.
- PRs welcome (docs, features, refactoring, fixes).
- See CONTRIBUTING.md

## 💡 Roadmap

_Work in progress – roadmap to be defined._  
Want to suggest a feature? [Open an issue](https://github.com/LibreBooking/app/issues) or join the [Discord discussion channel](https://discord.gg/4TGThPtmX8).

## 📜 License

This project is licensed under **GPL-3.0**.

## 🙏 Acknowledgments

Forked from Booked Scheduler. Thanks to all contributors and the community.

[Back to top](#librebooking)
