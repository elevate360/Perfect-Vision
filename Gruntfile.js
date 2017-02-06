module.exports = function(grunt){
	
	grunt.initConfig({
		
		pkg: grunt.file.readJSON('package.json'),
		
		/*Sass Task*/
		sass: {
			dist: {
				options: {
					style: 'expanded'
				},
				files: {
					'style.css' : 'sass/style.scss'
				}
			}			
		},
		/* Watch Task */
		watch: {
			css: {
				files: ['**/*.scss', 'inc/**/*/.scss'],
				tasks: ['sass']
			}
		}
		
	});
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.registerTask('default', ['watch']);
	
}
