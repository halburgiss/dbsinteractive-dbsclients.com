/** 
 * .sass_env.js
 *
 * This file is parsed by gulp and used to set the $domain for relative paths
 * in css files where we are using a cdn. It's required in the theme folder.
 * 2019-02-20
 *
 * $env has only 3 possible values: local, staging, or production
 * $cdn_domain is either 'false' or the full cdn domain
 */
module.exports = {
    env : 'local',
    cdn_domain  : 'false'
};
