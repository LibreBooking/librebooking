# Contributing to LibreBooking

First off, thank you for considering contributing to LibreBooking. We
appreciate your interest and effort. This project is open-source and needs
community contributions.

We welcome many forms of contributions, including but not limited to:

* Reporting bugs
* Suggesting new features or enhancements
* Improving documentation
* Writing tutorials or blog posts
* Submitting pull requests for bug fixes or new features

## Issue Management and Our Approach to Contributions

We value every contribution and bug report. However, as an open-source project
with limited maintainer resources, we rely heavily on the community to help us
move forward.

**Our Policy on Inactive Issues:**

To keep our issue tracker manageable and focused on actionable items, we have
the following approach:

* **We encourage reporters to propose solutions:** If you report an issue, we
strongly encourage you to also think about how it might be fixed and try to
implement that fix.
* **Community interest is key:** Issues that garner interest from the community
(e.g., multiple users confirming, discussions on solutions, offers to help) are
more likely to be addressed.
* **Closing inactive issues:** If an issue report doesn't receive a proposed
fix from the original reporter or anyone else in the community, and there's no
active discussion or indication that someone is willing to work on it after a
reasonable period, it may be closed.

  * When closing such an issue, we will typically leave a comment explaining
  that it's being closed due to inactivity and a lack of a proposed fix.

* **Reopening issues:** This doesn't mean the issue isn't valid. If you (or
someone else) are interested in working on a fix for a closed issue, please
comment on the issue. We are more than happy to reopen it and discuss your
proposed pull request or solution. We greatly appreciate it when community
members take ownership of fixing issues they care about.

We believe this approach helps us focus our efforts effectively and empowers
the community to contribute directly to the areas they are most passionate
about.

## Getting Started

* **Ensure you have an up-to-date `develop` branch:** Fork the repository and make
sure your `develop` branch is synced with the upstream project before creating
your feature branch.
* **Create a new branch** for your changes: `git checkout -b feature/your-feature-name` or `git checkout -b bugfix/issue-number-and-fix`.
* **Make your changes** and **add tests** if applicable.
* **Keep Composer files in sync:** If you change `composer.json`, regenerate and commit `composer.lock` in the same PR.
* **Ensure your code lints**
* **Commit your changes** with a clear and descriptive commit message.
* **Push your branch** to your fork: `git push origin feature/your-feature-name`.
* **Open a Pull Request** against our `develop` branch.

## Reporting Bugs

Before submitting a new bug report, please:

* **Check existing issues:** Search the issue tracker to see if the bug has already been reported.
* **Provide a clear title and description:** Explain the issue and include as much relevant information as possible.
* **Describe how to reproduce the bug:** Include steps, code snippets, or a minimal reproducible example.
* **Explain the expected behavior vs. actual behavior.**
* **Include details about your environment:** Operating system, project version, browser version, etc.

## Suggesting Enhancements

We'd love to hear your ideas for improving LibreBooking.

* **Check existing issues and discussions:** Your idea might have already been discussed.
* **Provide a clear title and description:** Explain the enhancement and why it would be beneficial.
* **Outline your proposed solution if you have one.**
* **Be open to discussion:** We may have questions or alternative suggestions.

## Pull Request Process

1. Ensure any install or build dependencies are removed before the end of the layer when doing a build.
2. Update the README.md with details of changes to the interface, this includes new environment variables, exposed ports, useful file locations, and container parameters.
3. Increase the version numbers in any examples files and the README.md to the new version that this Pull Request would represent. The versioning scheme we use is [SemVer](http://semver.org/).
4. Your PR will be reviewed by maintainers. We aim to review PRs in a timely manner, but please be patient.
5. Address any feedback or requested changes.
6. Once your PR is approved and CI checks pass, it will be merged. Thank you for your contribution.

## Commit Message Guidelines

We have precise rules over how our git commit messages should be formatted.
This leads to more readable messages that are easy to follow when looking
through the project history. This also enables us to automate our Changelog
generation at release time.

### Commit Message Format

Each commit message consists of a **header**, a **body** and a **footer**. The
header has a special format that includes a **type**, a **scope** and a
**subject**:

```text
<type>(<scope>): <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

The **header** with **type** is mandatory. The **scope** of the header is
optional as far as the automated PR checks are concerned, but be advised that
PR reviewers **may request** you provide an applicable scope.

Any line of the commit message should not be longer than 72 characters. This allows
the message to be easier to read on GitHub as well as in various git tools.

The footer should contain a reference to a GitHub Issue (e.g. #[number]).

Example 1:

```text
feat(API): Add new schedules endpoint

Add a new schedules endpoint which allows getting the resources of a schedule.

Closes: #2222
```

### AI Attribution

When an AI coding assistant contributed meaningfully to a commit (writing or
significantly modifying code, suggesting the fix, generating tests, etc.),
add an `Assisted-by` trailer to the commit footer:

```text
Assisted-by: <AgentName>:<ModelVersion>
```

Place it alongside any `Closes:` or `BREAKING CHANGE:` lines. Omit it when
the AI contribution was trivial (e.g. a one-word suggestion or only
autocomplete) or purely mechanical (running linters, reformatting).

Example:

```text
fix(reservations): correct overlap check for multi-day bookings

The start-of-day boundary was calculated in UTC rather than the
schedule's local timezone, causing false conflicts.

Closes: #1234
Assisted-by: Claude:claude-sonnet-4-6
```

### Revert

If the commit reverts a previous commit, it should begin with `revert:`,
followed by the header of the reverted commit. In the body it should say: `This
reverts commit <hash>.`, where the hash is the SHA of the commit being
reverted.

### Type

Must be one of the following:

* **feat**: A new feature
* **fix**: A bug fix
* **docs**: Documentation only changes
* **style**: Changes that do not affect the meaning of the code (white-space,
  formatting, etc)
* **refactor**: A code change that neither fixes a bug nor adds a feature
* **perf**: A code change that improves performance
* **test**: Adding missing tests or correcting existing tests
* **build**: Changes that affect the CI/CD pipeline or build system or external
  dependencies (example scopes: travis, jenkins, makefile)
* **ci**: Changes provided by DevOps for CI purposes.
* **revert**: Reverts a previous commit.
* **chore**: Other changes

### Scope

Should be one of the following:
Modules:

* **API**: A change or addition to the API
* *no scope*: If no scope is provided, it is assumed the PR does not apply to
  the above scopes
* Open to suggestions for other scopes. Use your judgement on what should be used.

### Body

Just as in the **subject**, use the imperative, present tense: "change" not "changed" nor "changes".
Here is detailed guideline on how to write the body of the commit message ([Reference](https://chris.beams.io/posts/git-commit/)):

```text
More detailed explanatory text, if necessary. Wrap it to about 72
characters or so. In some contexts, the first line is treated as the
subject of the commit and the rest of the text as the body. The
blank line separating the summary from the body is critical (unless
you omit the body entirely); various tools like `log`, `shortlog`
and `rebase` can get confused if you run the two together.

Explain the problem that this commit is solving. Focus on why you
are making this change as opposed to how (the code explains that).
Are there side effects or other unintuitive consequences of this
change? Here's the place to explain them.

Further paragraphs come after blank lines.

 - Bullet points are okay, too

 - Typically a hyphen or asterisk is used for the bullet, preceded
   by a single space, with blank lines in between, but conventions
   vary here
```

### Footer

The footer should contain a reference to a GitHub issue that this commit **Closes** or **Resolves**.
The footer should contain any information about **Breaking Changes**.

**Breaking Changes** should start with the word `BREAKING CHANGE:` with a space or two newlines.

### Pull Requests practices

* When merging, make sure git linear history is preserved. Maintainer should
  select a merge option (`Rebase and merge` or `Squash and merge`) based on which
  option will fit the best to the git linear history.
* PR topic should follow the same guidelines as the header of the Git Commit
  Message
