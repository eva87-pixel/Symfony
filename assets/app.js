import './styles/app.scss'; 
import './bootstrap'; 
import { createApp } from 'vue';
import { registerReactControllerComponents } from '@symfony/ux-react'; 

 
createApp({ 
  data() { 
    return { 
      compteur: 0 
    } 
  } 
}).mount('#app') 
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));