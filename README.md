# Current status

As some are aware, due to a chronic lack of time and health issues, I' haven't been the fastest to answer, implement new features or fix bugs., so I just want to inform everyone on the current status of the project.
At the moment my health is not the strongest, this combined with a heavy workload means that I'm simply not able to keep a very close eye on librebooking, this does NOT mean that this project is dead, in fact the usage on my workplace has steadily increased and will continue to do so in a nearby future, what this means is that in the coming months I won't be able to do much, besides fixing breaking bugs or merge pull requests.
I'm hoping this phase will pass quickly and that soon I will be able to a more active role, especially implementing new features.
So what can you do to help, first thing, fork the project, even if you are not a coder, fork the project, the more that have the code the better.
Second, if you found a bug submit an issue.
Third, if you managed to fix or trace the problem update the issue, even you can't code, others might be able to quickly provide a fix and maybe even submit a pr.
Finally if you can code, please contribute to the project even if it's something simple, like fixing grammatical errors all the help is appreciated.

## Community discussion channel on Discord

There is a community discussion channel on Discord at
<https://discord.gg/4TGThPtmX8>  (If this link doesn't work please file an Issue.
We set the link to never expire but for some reason it still keeps becoming
invalid)

## TODO list

Because LibreBooking is an opensource project, there are some things we have to do to make it better. Here is a list of things you can do if you're interested in helping out

## Welcome to LibreBooking

This is a community effort to keep the OpenSource [GPLv3](./LICENSE.md) LibreBooking alive, see [History](./doc/HISTORY.md)

### Note

The update project is currently in the beta phase; testing and participation from all users and administrators are required.

## What's new?

- Update to Bootstrap 5 and migration of icons to Bootstrap Icons.

![Update to Bootstrap 5 and migration of icons to Bootstrap Icons](./Web/img/readme/01.png)

- Design changes.

![Design changes](./Web/img/readme/02.png)
![Design changes](./Web/img/readme/03.png)
![Design changes](./Web/img/readme/04.png)
![Design changes](./Web/img/readme/05.png)
![Design changes](./Web/img/readme/06.png)
![Design changes](./Web/img/readme/07.png)
![Design changes](./Web/img/readme/08.png)

- "Back to top" button on all pages.

!["Back to top" button on all pages](./Web/img/readme/09.png)

- Rename Web/booked.css to Web/librebooking.css (this is no longer Booked Schedule ;-) ).

- Ability to change the color scheme in `config.php $conf['settings']['css.theme'] = 'default'`  and/or customize an existing one in `Web/css/librebooking.css`

![color scheme](./Web/img/readme/10.png)
![color scheme](./Web/img/readme/11.png)
![color scheme](./Web/img/readme/12.png)
![color scheme](./Web/img/readme/13.png)
![color scheme](./Web/img/readme/14.png)

- Use of DataTables.

![Use of DataTables](./Web/img/readme/15.png)

- Removal of obsolete libraries (e.g. Owl, FloatThead).

## Prerequisites

- PHP 8.1 or greater
- MySQL 5.5 or greater
- Web server (Apache, IIS)

## Installation instructions

[Full install instructions](./doc/INSTALLATION.md)

## Developer Documentation

[developer documentation](./doc/README.md)

## Help

Please consult the wiki for more help <https://github.com/LibreBooking/app/wiki>

## REPO

<https://github.com/LibreBooking/app>

## ReCaptcha

09/03/2023 - The ReCaptcha integration has been updated to v3, you will need to generate new keys for your website if you are using it on your website.

## Docker usage

For information on how to use LibreBooking in a Docker container see:
<https://github.com/LibreBooking/docker>
