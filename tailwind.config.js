module.exports = {
  content: [
     './storage/framework/views/*.php',
     './resources/**/*.blade.php',
     './resources/**/*.js',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily:{
        'title':['Sansita', 'sans-serif'],
        'body':['Lato', 'sans-serif']
      },
      colors:{
        'pri':'#081F4D',
        'sec':'#0083FF',
        'gen':'#3B4D71',
        'our-bg':'#D9E6FF'
      }


    },
  },
  plugins: [require('@tailwindcss/forms')],
}
