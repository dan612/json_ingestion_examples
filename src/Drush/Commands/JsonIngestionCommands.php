<?php

namespace Drupal\json_ingestion_examples\Drush\Commands;

use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drush\Commands\DrushCommands;

/**
 * Drush command file.
 */
class JsonIngestionCommands extends DrushCommands {

  /**
   * A custom Drush command to displays the given text.
   *
   * @command json-ingestion-examples:update-posts
   *   The amount to iterate over
   * @aliases jie-posts
   */
  public function updatePosts() {
    $this->importImages();
    $this->importPosts();
  }

  /**
   * Imports all images.
   */
  public function importImages() {
    // Go fetch JSON for images.
    $http_client = \Drupal::httpClient();
    $images_json_url = 'https://jsonplaceholder.typicode.com/photos';
    $images_json = $http_client->request('GET', $images_json_url)->getBody()->getContents();
    // Reduce to 100 images as only have 100 posts.
    $images_array = array_slice(json_decode($images_json), 0, 100);
    $directory = 'public://drush-generated';
    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');
    // Make sure directory exists.
    $file_system->prepareDirectory($directory, FileSystemInterface:: CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
    foreach ($images_array as $img_ob) {
      // Download file, always replace.
      $file = file_get_contents($img_ob->url);
      $filename = basename($img_ob->url) . '.jpeg';
      $filepath = $directory . '/' . $filename;
      $file_system->saveData($file, $filepath, FileSystemInterface::EXISTS_REPLACE);
      // Create file entity.
      $file = File::create([
        'filename' => $filename,
        'uri' => $filepath,
        'status' => 1,
        'uid' => 1,
      ]);
      if ($file->save()) {
        $this->writeln("File $filename created");
      }
    }
  }

  /**
   * Imports all posts.
   */
  public function importPosts() {
    // Go fetch JSON for posts.
    $http_client = \Drupal::httpClient();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $posts_json_url = 'https://jsonplaceholder.typicode.com/posts';
    $posts_json = $http_client->request('GET', $posts_json_url)->getBody()->getContents();
    $posts_array = json_decode($posts_json);
    foreach ($posts_array as $post) {
      $node_check = $node_storage->loadByProperties([
        'type' => 'post',
        'title' => $post->title
      ]);
      // If title already exists, skip.
      if ($node_check) {
        continue;
      }
      $new_post = Node::create(['type' => 'post']);
      $new_post->set('title', $post->title);
      $new_post->set('body', $post->body);
      $new_post->set('field_image', $post->id);
      $new_post->enforceIsNew();
      if ($new_post->save()) {
        $this->writeln("New post created: $post->title");
      }
    }
  }
}
