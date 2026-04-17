# Parts V2

This application will provide a directory of LEGO® colours, parts, and sets. Quite similar to [Brick Owl](https://www.brickowl.com/) and [Rebrickable](https://rebrickable.com/). The app will collect the original data from the [Rebrickable API](https://rebrickable.com/api/).

> The live website is available at:  
> https://parts.brickmmo.com

> Database Structure:  
> https://rebrickable.com/static/img/diagrams/downloads_schema_v3.png

## Setup

Follow these instructions to get the parts application up and running locally:

1) Clone the repo to your local environment:

  ```
  git clone https://github.com/BrickMMO/parts-v2.git
  cd parts-v2
  ```
  
2) Start MAMP, open phpMyAdmin and create a new database called `brickmmo_parts`.

3) Make a copy of the `.env.sample` file and name it `.env`. Change the values as follows:

  ```
  DB_HOST=localhost
  DB_DATABASE=brickmmo_parts
  DB_USERNAME=root
  DB_PASSWORD=root

  PER_PAGE=10

  SITE_URL=http://localhost

  ENV_LOCAL=true
  ```

  > [!NOTE]  
  > Change the host and password based on your MAMP settings. 

4) 




---

## Project Stack

This application will intentionally be written in a simplified structure with a simple stack. This will provide BrickMMO developers with a simple application to contribute to once they have some understanding of server-side languages such as PHP.

<img src="https://console.codeadam.ca/api/image/php" width="60"> <img src="https://console.codeadam.ca/api/image/mysql" width="60"> <img src="https://console.codeadam.ca/api/image/w3css" width="60">

---

## Repo Resources

- [Parts](https://parts.brickmmo.com)
- [PHP](https://php.net)
- [BrickMMO](https://brickmmo.com)

<a href="https://brickmmo.com">
<img src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png" width="200">
</a>
