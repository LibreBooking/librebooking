# Security Policy

## Supported Versions

Only the most current stable version receives patches for security
vulnerabilities.

supported_version: 5.0.2

## Reporting a Vulnerability

Report suspected security vulnerabilities to
**[librebooking@outlook.com](mailto:librebooking@outlook.com)**.

LibreBooking is maintained by a small volunteer team. Please allow 2-5 days for
an initial response. If the issue is confirmed, a patch will be released as
soon as practical depending on severity, complexity, and maintainer
availability.

## Report Requirements

Reports should include:

- Affected LibreBooking version or commit
- Attacker role and prerequisites
- Clear reproduction steps
- Request/response examples or screenshots, where applicable
- Expected behavior and actual behavior
- Security impact
- Whether the issue has been publicly disclosed

Reports that only include scanner output, code-search results, AI-generated
summaries, or references to historical CVEs without a working reproduction
against a supported LibreBooking version may be closed without investigation.

## Out of Scope

The following are generally out of scope unless accompanied by a clear exploit
path and security impact:

- Missing security headers
- Version disclosure
- Self-XSS
- Logout CSRF
- Reports requiring application administrator access
- Dependency CVEs without evidence that vulnerable code is reachable in LibreBooking
- Theoretical issues without reproduction steps
- Social engineering or physical attacks
- Denial-of-service reports based only on excessive traffic volume

## CVE Policy

LibreBooking does not request, assign, or coordinate CVE IDs.

Security issues may be fixed in public commits, release notes, or GitHub
advisories at the maintainers' discretion. Reporters who want a CVE must
coordinate it independently and should not expect maintainer participation.

Reports submitted primarily to obtain CVE assignment will be closed. Do not
submit reports solely to request CVE assignment. We do not provide CVE
sponsorship, CVE write-ups, embargo coordination for CVE publication, or
repeated status updates for CVE records.

## Administrator Trust Model

Grant application, group, resource, and schedule administrator permissions only
to trusted users. Administrators can change configuration, users, groups,
resources, schedules, announcements, reservations, templates, and other content
that may be displayed to other users or sent by email.

LibreBooking treats administrator accounts as privileged operators, not as
untrusted users. A malicious or compromised administrator account can affect the
confidentiality, integrity, and availability of a LibreBooking installation.
Use strong passwords, revoke unused administrator access, and assign the
smallest administrator role needed for the user's responsibilities.
