module.exports = (grunt) ->
    grunt.loadNpmTasks 'grunt-contrib-clean'
    grunt.loadNpmTasks 'grunt-contrib-less'
    grunt.loadNpmTasks 'grunt-autoprefixer'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-uglify'
    grunt.loadNpmTasks 'grunt-spritesmith'

    serverDir = './web'

    getSpriteSettings = ->
        sprites = [
            'animation-deus'
            'icons'
            'teachers'
            'partners'
        ]

        settings = {}

        for spriteName in sprites
            do (spriteName) ->
                spritesSetting =
                    engine:    'pngsmith'
                    algorithm: 'binary-tree'
                    src:       "#{serverDir}/img/#{spriteName}/*.png"
                    dest:      "#{serverDir}/assets/sprites-#{spriteName}.png"
                    destCss:   "#{serverDir}/css/sprites/sprites-#{spriteName}.less"
                    # cssFormat: 'less'
                    imgPath:   "/assets/sprites-#{spriteName}.png?<%= grunt.template.today('yyyymmddHHmmss') %>"
                    cssVarMap: (sprite) =>
                        sprite.name = "icon-#{spriteName}-#{sprite.name}";
                        return;

                settings[spriteName] = spritesSetting

        settings

    grunt.initConfig(
        'constants':
            banner: '/* <%= grunt.template.today("yyyy-mm-dd HH:mm:ss") %> */'

        'clean':
            options: { force: true }
            'before-sprites': [
                "#{serverDir}/assets/*.png"
                "#{serverDir}/css/sprites/*.*"
            ]
            'before-css': [ "#{serverDir}/assets/*.css" ]
            'before-js': [ "#{serverDir}/assets/*.js" ]

        'sprite': getSpriteSettings()

        'less':
            options:
                compress: true
            css:
                files: [
                    expand: true
                    cwd:    "#{serverDir}/css"
                    src:    ['page-*.less']
                    dest:   "#{serverDir}/assets"
                    ext:    '.css'
                ]

        'coffee':
            options:
                bare: true
                join: false
            js:
                files: [
                    expand: true
                    cwd:    "#{serverDir}/js"
                    src:    ['*.coffee']
                    dest:   "#{serverDir}/assets"
                    ext:    '.js'
                ]

        'uglify':
            options:
                banner: '<%= constants.banner %>\n'
                compress: true
                beautify: false
                preserveComments: false
            dest:
                files: [
                    expand: true
                    cwd: "#{serverDir}/assets"
                    src: '*.js'
                    dest: "#{serverDir}/assets"
                ]

        'autoprefixer':
            options:
                diff: !true
            dest:
                files: [
                    expand: true
                    cwd: "#{serverDir}/assets"
                    src: '*.css'
                    dest: "#{serverDir}/assets"
                ]

    )

    grunt.registerTask 'build-sprites', [
        'clean:before-sprites'

        'sprite'
        'build-css'
    ]

    grunt.registerTask 'build-css', [
        'clean:before-css'
        'less'
        'autoprefixer'
    ]

    grunt.registerTask 'build-js', [
        'clean:before-js'
        'coffee'
        'uglify'
    ]

    grunt.registerTask 'build-all', [
        'build-sprites'
        'build-css'
        'build-js'
    ]

    grunt.registerTask 'default', 'build-all'