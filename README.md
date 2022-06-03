# How to use this template

This template can be used to quickly scaffold a Drupal project (module, theme, profile, etc.) to begin remediating code for Drupal 10 Compatibility.

## How to use this template to create a starter project

### Gather information
1. Visit the project page for the selected project on Drupal.org
1. Note the most recent D8 or D9 dev version of the project (usually found under "Releases", in the format of "8.x-1.x-dev, or "3.2.x-dev")
   Look for a line like "Development version: 2.0.x-dev updated 26 Apr 2022 at 10:58 UTC".
1. Make a note of the machine name of the project (listed after `https://www.drupal.org/project/` in the page URL).
1. Make a note of the Human Readable project name, which is the displayed name of the project on the page, below the tabbed menu which is directly below "Download & Extend".

You will need the name, machine name, and dev version in the
[Customize your repository](#customize-your-repository)
steps below.

### Create your repository

1. Click the "Use this template" button in the menubar
1. On the "Create a new repository from _project-template" page, change the owner to "Drupal-10-Compatibility", and in the "Repository name" field, add the machine name of the project as it is specified on Drupal.org.
1. Select "public" repository
1. Click Create repository from template
1. Wait until your project is created, then clone the project locally (follow next instructions)

### Customize your repository

Prerequisite: you need PHP and Composer installed locally.
Do not use `ddev composer`.

1. Remove the line `drupal/project_name` from `composer.json` but save it.
1. Run the command `composer install`.
1. Restore the line you removed in Step 1.
1. Run the command `composer show --all drupal/project_name`. The `versions`
   line shows the available branches and tags. One of these should match the
  "dev version" you noted above. You may have to strip `8.x-`.
1. Run the command `composer create-project` and enter the project name, machine
   name, and branch that you noted above.
1. Review the changes made by that command.
1. Commit all changes to the files in the project to the repository, and push up your changes.
1. You're all set and ready to go.  Github actions will run coding standards checks, static analysis for deprecations, and execute any unit tests that the project has in it's codebase.
1. Work on code remediation in the form of patches until all Github Actions jobs execute successfully.
1. Submit your patches to the project on Drupal.org!

Welcome to the Drupal Community, we appreciate your contributions :). Happy Coding!

<!-- Delete all lines above here when creating a project from this template, after following the above instructions -->
# project_label

## Overview

The purpose of this project is to leverage Github Actions to build a Drupal 10 site, install the above module, and conduct the following:

* Static Analysis:  Use phpstan-drupal extension for phpstan to conduct a static scan of the module codebase for deprecations for both PHP 8 and Drupal 10.
* Coding Standards:  Check for compliance with Drupal Coding Standards.
* Unit Tests:  Execute any unit tests included in the module's codebase to make sure that unit testing integrity is intact after patching for deprecations and coding standards compliance.

## Test Results

| Analysis Type | Results | Test Run |
| ----- | ----- | ----- |
| Static Analysis: | ![Static Analysis](https://github.com/Drupal-10-Compatibility/project_name/actions/workflows/static_analysis.yml/badge.svg) | [Static Analysis](https://github.com/Drupal-10-Compatibility/project_name/actions/workflows/static_analysis.yml) |
| Coding Standards: | ![Coding Standards](https://github.com/Drupal-10-Compatibility/project_name/actions/workflows/coding_standards.yml/badge.svg) | [Coding Standards](https://github.com/Drupal-10-Compatibility/project_name/actions/workflows/coding_standards.yml) |
| Unit Tests: | ![Unit Tests](https://github.com/Drupal-10-Compatibility/project_name/actions/workflows/unit_tests.yml/badge.svg) | [Unit Tests](https://github.com/Drupal-10-Compatibility/project_name/actions/workflows/unit_tests.yml) |

## Working on the project locally with DDEV

1. Execute `ddev start` and `ddev install` to install a working copy of Drupal
   with this project for unit testing purposes.
1. That command finishes with `ddev drush uli`, providing you with a one-time
   login link.
1. View the status of the installed project at Reports > Upgrade status
   (`/admin/reports/upgrade-status`).
1. Use `ddev` to list available commands. Use `ddev cmd --help` or `ddev help
   cmd` for more information on a specific command. This project defines at
   least the following custom commands:
   - `coding-standards`
   - `install`
   - `static-analyze`
   - `unit-tests`
