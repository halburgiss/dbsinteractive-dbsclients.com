The designer will typically provide the favicon image files (*.ico, *.png, etc.). You may drag these image files into this directory (/baserepo/wp-content/themes/slate/library/favicons), where they will be called. Filenames should reflect the following list:

 * android-chrome-36x36.png
 * android-chrome-48x48.png
 * android-chrome-72x72.png
 * android-chrome-96x96.png
 * android-chrome-144x144.png
 * android-chrome-192x192.png
 * android-chrome-256x256.png
 * android-chrome-384x384.png
 * android-chrome-512x512.png
 * apple-touch-icon.png
 * apple-touch-icon-57x57.png
 * apple-touch-icon-60x60.png
 * apple-touch-icon-72x72.png
 * apple-touch-icon-76x76.png
 * apple-touch-icon-114x114.png
 * apple-touch-icon-120x120.png
 * apple-touch-icon-144x144.png
 * apple-touch-icon-152x152.png
 * apple-touch-icon-180x180.png
 * favicon-16x16.png
 * favicon-32x32.png
 * favicon-194x194.png
 * favicon.ico


Utilities.php (located @ /baserepo/wp-content/themes/slate/Base/Utilities.php) contains the code that calls in the favicons partial around line 25 (as of 2/2017).

The favicons partial is located at /baserepo/wp-content/themes/slate/views/partials/favicons.php. You shouldn't have to touch this code, however, this note exists for debugging purposes.
