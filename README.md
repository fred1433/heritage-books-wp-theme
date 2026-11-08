# Heritage Books

A classic WordPress theme for a children's history publisher with films and books: home page, film list by era, film page with a sample player, book page with store links. Written with Claude Code; the log of how is in [BUILD_LOG.md](BUILD_LOG.md).

This repository holds the theme and fictional data only.

## Try it

You need Node 20 or later. No PHP or database: [WordPress Playground](https://wordpress.github.io/wordpress-playground/) runs WordPress locally.

```sh
git clone https://github.com/fred1433/heritage-books-wp-theme.git
cd heritage-books-wp-theme
npx @wp-playground/cli@3.1.62 server --auto-mount --blueprint=tools/preview.blueprint.json --login
```

Open the address it prints. You get the fictional publisher in `sample-data/fictional.json`. To stop, press Ctrl+C. The content lives in memory, so a restart goes back to the sample data.

## What you edit, and where

- **Films and Books** in the WordPress menu: title and description in the block editor, the other fields in the box under it.
- **Site content** in the WordPress menu: every text on the home page, the footer and the labels.
- **Eras** come from the content file and appear in the home timeline and the film list.

`tools/seed.php` loads a content file into a blank install. A copy of `sample-data/fictional.json` with your own text is how a new site starts.

## What is not in the theme

Video subscriptions, sign-in, checkout and store integrations need the site owner's accounts. Store buttons are plain links. Film playback here is a local file.

## License

GPL-2.0-or-later. Fonts: SIL Open Font License, see `assets/fonts`.
