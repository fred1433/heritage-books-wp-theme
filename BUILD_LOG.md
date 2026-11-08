# Build log

Written with Claude Code in one session on 8 November 2026. Edited to remove anything about a specific client.

1. Read the design screens and listed what each screen needs: order of sections, actions, states. Wrote the theme from scratch without reusing any file from the design sources.
2. Chose a classic PHP theme so that a non-developer edits titles in the block editor and everything else in plain form fields.
3. No PHP on the machine, so WordPress runs in WordPress Playground (PHP compiled to WebAssembly, SQLite, in memory). One command boots it and loads the content file.
4. First pass: film and book content types, an era taxonomy, a "Site content" screen generated from the settings, templates, CSS, a little JavaScript. Pages render without the JavaScript.
5. Checked each page at 390, 820 and 1440 px. Fixes that came from looking at the screenshots:
   - the age badge hid the first letters of the book titles;
   - a label on film posters covered the poster title on phones;
   - the winding chart ran off its sheet;
   - a shadow behind the film title on phones dimmed the text under it;
   - a long word on a film poster was clipped, and the gold lettering lost contrast at its darkest;
   - a video with a fixed height showed as a tall black box on phones.
6. Back from a film to the list had to land on the same card: the list stores its position when a card is clicked and restores it on return. Images have fixed proportions so the page does not shift.
7. Tested the editor for real: changed a book title in the block editor, saved, and the new title appeared on the book page, its cover and the breadcrumb.
8. Ran the commands in the README from a fresh clone.
