<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/figuren-theater/ft-admin-ui">
    <img src="https://raw.githubusercontent.com/figuren-theater/logos/main/favicon.png" alt="figuren.theater Logo" width="100" height="100">
  </a>

  <h1 align="center">figuren.theater | Admin UI</h1>

  <p align="center">
    Clean and helpful UI to make digital content editing with *your* <a href="https://websites.fuer.figuren.theater">websites.fuer.figuren.theater</a> a friendly experience.
    <br /><br /><br />
    <a href="https://meta.figuren.theater/blog"><strong>Read our blog</strong></a>
    <br />
    <br />
    <a href="https://figuren.theater">See the network in action</a>
    •
    <a href="https://mein.figuren.theater">Join the network</a>
    •
    <a href="https://websites.fuer.figuren.theater">Create your own network</a>
  </p>
</div>

## About 


This is the long desc

* [x] *list closed tracking-issues or `docs` files here*
* [ ] Do you have any [ideas](/issues/new) ?

## Background & Motivation

...

## Install

1. Add this repository to your `composer.json`
```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/figuren-theater/ft-admin-ui"
    }
]
```

2. Install via command line
```sh
composer require figuren-theater/ft-admin-ui
```

## Usage

### API

```php
Figuren_Theater::API\get_...()
```

## Plugins included

This package contains the following plugins. 
Thoose are completely managed by code and lack of their typical UI.

* [Disable Gutenberg Blocks](https://wordpress.org/plugins/disable-gutenberg-blocks/#developers)
* [Emoji Toolbar](https://wordpress.org/plugins/emoji-toolbar/#developers)
* [Heartbeat Control](https://wordpress.org/plugins/heartbeat-control/#developers)
* [Multisite Enhancements](https://wordpress.org/plugins/multisite-enhancements/#developers)


## What does this package do in addition?

Accompaniying the core functionality of the mentioned plugins, theese **best practices** are included with this package.

[X] Add some Admin Menu Notification Bubbles with the count of pending reviews for each post_type.
[X] Add Featured Images in an Admin Column and in Quick Edit


This also includes changes to **Dashboard Widgets**:

[X] Show drafts of all public post_types inside the *Recent Drafts* Widget.
[X] Show the last changelog from [websites.fuer.figuren.theater](https://websites.fuer.figuren.theater/) and the two latest news from [meta.figuren.theater](https://meta.figuren.theater/) as its own Widget.


## Built with & uses

  - [dependabot](/.github/dependabot.yml)
  - ....

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


## Versioning

We use [Semantic Versioning](http://semver.org/) for versioning. For the versions
available, see the [tags on this repository](/tags).

## Authors

  - **Carsten Bach** - *Provided idea & code* - [figuren.theater/crew](https://figuren.theater/crew/)

See also the list of [contributors](/contributors)
who participated in this project.

## License

This project is licensed under the [GPL-3.0-or-later](LICENSE.md), see the [LICENSE](LICENSE) file for
details

## Acknowledgments

  - [altis](https://github.com/search?q=org%3Ahumanmade+altis) by humanmade, as our digital role model and inspiration
  - [@roborourke](https://github.com/roborourke) for his clear & understandable [coding guidelines](https://docs.altis-dxp.com/guides/code-review/standards/)
  - [python-project-template](https://github.com/rochacbruno/python-project-template) for their nice template->repo renaming workflow
