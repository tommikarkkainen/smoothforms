# smoothforms

Web form generator and handler, especially for static sites running on web
hosting.

## Key features

* Define forms using simple JSON files
* Validators allow you to make sure your forms are used the way you intend to
* Honeypot fields to deter spam
* Easy to deploy in most webhosting providers
* Perfect for use with static sites (either pure HTML or generated with a
  static site generator, e.g. [Zola](https://www.getzola.org)
* Requires no additional libraries

## Requirements

smoothforms v1.0 has been tested with PHP 7.4 and PHP 8.0

URL rewriting is supported for Apache web servers with mod_rewrite enabled.

*No other dependencies!*

# Documentation

User documentation including a 'getting started' for quickly trying smoothforms
outcan be found in the
[Wiki](https://github.com/tommikarkkainen/smoothforms/wiki)

# Code repository information

1. All PRs must include updates to the documentation to reflect the changes
   made to the behaviour of smoothforms. User documentation is located in the
   [Wiki](https://github.com/tommikarkkainen/smoothforms/wiki)
2. `main` branch is the main development branch! It is unsafe to deploy its
   contents into production. All pull requests should be to `main`
3. `release` branch must always be production-ready. No pull requests against
   `release`, please.
