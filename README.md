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
Will import the first 100 images only, followed by 100 posts. 
Also available for viewing on `/migration-posts` after import.
```
drush jie-posts
```
### Approach 3: Javascript Only
 1. Attach a javascript library to a page
 2. Fetch the API data and render it all client side
