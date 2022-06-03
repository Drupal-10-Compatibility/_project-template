<?php declare(strict_types=1);

namespace Drupal10;

use Composer\Installer\PackageEvent;
use Composer\Script\Event;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Yaml\Yaml;

/**
 * Contains command callbacks to initialize the repository.
 */
final class ComposerScripts {

  /**
   * Interactively customize the project.
   *
   * @param \Composer\Script\Event $event
   */
  public static function interactiveConfiguration(Event $event): void {
    $io = $event->getIO();

    $projectLabel = 'MyDrupal Project';
    $projectLabel = $io->ask("Project name [$projectLabel]: ", $projectLabel);
    $projectName = str_replace(' ', '_', strtolower($projectLabel));
    $projectName = $io->ask("Project machine name [$projectName]: ", $projectName);
    $projectVersion = '2.0.x-dev';
    $projectVersion = $io->ask("Project branch (include '-dev') [$projectVersion]: ", $projectVersion);
    $ddevName = str_replace('_', '-', $projectName);
    $ddevName = $io->ask("DDev project name [$ddevName]: ", $ddevName);

    $io->write([
      '',
      'You have entered:',
      "Project name: <warning>$projectLabel</warning>",
      "Project machine name: <warning>$projectName</warning>",
      "Project branch: <warning>$projectVersion</warning>",
      "DDev project name: <warning>$ddevName</warning>",
    ]);
    if (!$io->askConfirmation('Is this correct (Yes/no)? ')) {
      $io->writeError("<error>Aborted. To try again, run 'composer create-project' from the project directory.</error>");
      return;
    }

    // Replace tokens with interactively supplied values.
    $projectRoot = dirname($event->getComposer()->getConfig()->get('vendor-dir'));
    // Make sure to replace 'ddev_project_name' before replacing 'project_name'.
    static::fileReplaceToken("$projectRoot/.ddev/config.yaml", 'ddev_project_name', $ddevName);
    // Require the project to be tested.
    static::fileReplaceToken("$projectRoot/composer.json", "\"drupal/project_name\": \"^1.0.0\"", "\"drupal/$projectName\": \"^$projectVersion\"");
    $files = [
      'README.md',
      '.ddev/config.yaml',
      '.github/workflows/coding_standards.yml',
      '.github/workflows/static_analysis.yml',
      '.github/workflows/unit_tests.yml',
      'composer.json',
    ];
    foreach ($files as $file) {
      static::fileReplaceToken("$projectRoot/$file", 'project_name', $projectName);
      static::fileReplaceToken("$projectRoot/$file", 'project_label', $projectLabel);
    }

    // Remove all contents of the `README.md` file down to the comment.
    $path = "$projectRoot/README.md";
    $parts = preg_split('/<!-- Delete all lines above here.*-->\n/', file_get_contents($path));
    file_put_contents($path, array_pop($parts));
  }

  /**
   * Update a file by replacing a token.
   *
   * @param string $fileName
   *   The full path to the file to be updated.
   * @param string $token
   *   The token to be replaced.
   * @param string $value
   *   The project name for Fru tools, such as drupal-skeleton.
   */
  protected static function fileReplaceToken(string $fileName, string $token, string $value): void {
    $file = file_get_contents($fileName);
    $file = str_replace($token, $value, $file);
    file_put_contents($fileName, $file);
  }

}
