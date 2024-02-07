## JSON Ingestion Examples

The data is pulled from https://jsonplaceholder.typicode.com/

### Approach 1: Migrate API
#### Requirements
- Migrate
- Migrate Plus

There are 2 migrations:
 - images (5000 total)
 - json_posts_to_posts (100 total)

To run them you can do:
```
drush mim images --limit=100
drush mim json_posts_to_posts
```
there are only 100 posts, so for our purposes we don't need more than 100 images.

Once both migrations have been run you can see the data at `/migration-posts` which is controller by a view.
This approach has the advantage of requiring migrate API which pulls in a lot of helpful things,
such as rollbacks, update old items, only importing new items...etc.
### Approach 2: Custom Drush Command
 1. Create drush command to get images, get posts
 2. Create view of drush command based posts
### Approach 3: Views JSON Source
#### Requirements
- views_json_source

Make sure `views_json_source` module is included in your codebase.
Then visit `/views-json-source` after enabling this module which is a preconfigured view of titles and body fields from external JSON.

### Approach 4: Javascript Only
 1. Attach a javascript library to a page
 2. Fetch the API data and render it all client side
