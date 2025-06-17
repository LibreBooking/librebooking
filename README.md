
# Librebooking

[![GitHub issues](https://img.shields.io/github/issues/LibreBooking/app)](https://github.com/LibreBooking/app/issues)
[![Last commit](https://img.shields.io/github/last-commit/LibreBooking/app)](https://github.com/LibreBooking/app/commits)
[![GitHub release](https://img.shields.io/github/v/release/LibreBooking/app?include_prereleases)](https://github.com/LibreBooking/app/releases)
[![License: GPL v3](https://img.shields.io/badge/license-GPLv3-blue.svg)](./LICENSE.md)

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

**LibreBooking** is an open-source scheduling solution, forked from Booked Scheduler. It offers a flexible, mobile-friendly, and extensible interface for organizations to manage resource reservations. The repository for the last open source version of Booked Scheduler is maintained here; the `develop` branch contains the latest working code (beta), while `master` is the stable release.

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

## 📸 Screenshots

![Login](./Web/img/readme/02.png)
![Schedules](./Web/img/readme/06.png)
![Dashboard](./Web/img/readme/03.png)
![User profile](./Web/img/readme/04.png)
![Search](./Web/img/readme/07.png)
![DataTables example](./Web/img/readme/15.png)

## 🔧 Installation & Deployment

### Manual Installation

For full setup instructions, see [INSTALLATION.md](./doc/INSTALLATION.md)

### Docker Deployment

LibreBooking is available as a Docker container. See [LibreBooking Docker README](https://github.com/LibreBooking/docker) for complete setup.

```bash
git clone https://github.com/LibreBooking/docker.git
cd docker
docker-compose up -d
```

## 💻 Developer Documentation

- See [doc/README.md](./doc/README.md) for developer notes.
- See [doc/API.md](./doc/API.md) for API notes.
- See [doc/Oauth2-Configuration.md](./doc/Oauth2-Configuration.md) for Oauth2 configuration.
- See [doc/SAML-Configuration.md](./doc/SAML-Configuration.md) for SAML configuration.
- Codebase follows PSR-12 standards and GitHub Flow.

## 🎨 Configuration & Theming

- Change theme via `config.php`:

  ```php
  $conf['settings']['css.theme'] = 'default';
  ```

- Theme options: 'default', 'dimgray', 'dark_red', 'dark_green', 'french_blue', 'pastel_blue'
- Customize `Web/css/librebooking.css`.

## 🔒 ReCaptcha

As of 09/03/2023, ReCaptcha integration updated to v3. Generate new keys for your domain if using ReCaptcha.

## 💬 Community & Support

- [Discord](https://discord.gg/4TGThPtmX8)
- [Wiki](https://github.com/LibreBooking/app/wiki)
- [Issues](https://github.com/LibreBooking/app/issues)
- [Discussions](https://github.com/LibreBooking/app/discussions)

## 🤝 Contributing

- Fork, file issues, suggest improvements.
- Even non-coders can help by reporting bugs, testing, updating issues.
- PRs welcome (docs, features, refactoring, fixes).
- See CONTRIBUTING.md if present.

## 💡 Roadmap

_Work in progress – roadmap to be defined._  
Want to suggest a feature? [Open an issue](https://github.com/LibreBooking/app/issues) or join the [Discord discussion channel](https://discord.gg/4TGThPtmX8).

## 📜 License

This project is licensed under **GPL-3.0**.

## 🙏 Acknowledgments

Forked from Booked Scheduler. Thanks to all contributors and the community.

[Back to top](#librebooking)
