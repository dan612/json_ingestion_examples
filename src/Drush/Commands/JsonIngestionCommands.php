<?php

namespace Drupal\json_ingestion_examples\Drush\Commands;
use Drush\Commands\DrushCommands;

/**
 * Drush command file.
 */
class JsonIngestionCommands extends DrushCommands {

  /**
   * A custom Drush command to displays the given text.
   *
   * @command json-ingestion-examples:update-posts
   * @param $limit
   *   The amount to iterate over
   * @aliases jie-posts
   */
  public function updatePosts($limit = 0) {
    if ($limit > 0) {
      $total = 10;
    }
    $this->writeln("hello");
    // Go fetch JSON for images.
    // Copy all images to local files.
    // Iterate over all posts to bring them in.
    // Iterate over all of it storing in posts.
  }
}
