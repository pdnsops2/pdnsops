module.exports = function (grunt) {
    'use strict';
    var extIbuttonAsset = 'extensions/ibutton/resources/css/';
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        bumpup: ['package.json', 'composer.json'],

        release: {
            options: {
                bump: false, //default: true
                tagName: 'v<%= version %>', //default: '<%= version %>'
                commitMessage: 'release v<%= version %>', //default: 'release <%= version %>'
                tagMessage: 'tagging version v<%= version %>' //default: 'Version <%= version %>'
            }
        },

        replace: {
            readme: {
                src: ['README.md'],
                overwrite: true,
                replacements: [
                    {
                        from: /Version \d{1,1}\.\d{1,2}\.\d{1,2}/g,
                        to: 'Version <%= pkg.version %>'
                    }
                ]
            }
        },
        sass: {                              // Task
            ibuttondist: {                            // Target
                options: {                       // Target options
                    style: 'compact'
                },
                files: {                         // Dictionary of files
                    'extensions/ibutton/resources/css/jquery.ibutton.css': extIbuttonAsset+'jquery.ibutton.scss'       // 'destination': 'source'
                }
            },
            ibuttonprod:{
                options: {                       // Target options
                    style: 'compressed'
                },
                files: {                         // Dictionary of files
                    'extensions/ibutton/resources/css/jquery.ibutton.min.css': extIbuttonAsset+'jquery.ibutton.scss'       // 'destination': 'source'
                }
            }
        },
        watch: {
            ibuttoncss: {
                files: extIbuttonAsset+'jquery.ibutton.scss',
                tasks: ['sass:ibuttondist','sass:ibuttonprod']

            }

        }
    });

    grunt.loadNpmTasks('grunt-release');
    grunt.loadNpmTasks('grunt-bumpup');
    grunt.loadNpmTasks('grunt-text-replace');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['replace', 'release']);

};