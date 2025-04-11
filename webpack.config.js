const Encore = require('@symfony/webpack-encore');

// Configure l'environnement runtime si ce n'est pas déjà fait
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Dossier de sortie des fichiers compilés
    .setOutputPath('public/build/')
    // Chemin public pour accéder aux fichiers compilés
    .setPublicPath('/build')

    /*
     * Configuration des entrées
     * Chaque entrée produit un fichier JavaScript (ex. app.js)
     * et un fichier CSS (ex. app.css) si le JS importe des styles.
     */
    .addEntry('app', './assets/app.js')

    // Optimisation des fichiers (division en morceaux plus petits)
    .splitEntryChunks()

    // Active le runtime.js pour les projets multi-pages
    .enableSingleRuntimeChunk()

    /*
     * Configuration des fonctionnalités supplémentaires
     */
    .cleanupOutputBeforeBuild() // Nettoie le dossier de sortie avant chaque compilation
    .enableBuildNotifications() // Notifications pour la compilation
    .enableSourceMaps(!Encore.isProduction()) // Active les sourcemaps en mode dev
    .enableVersioning(Encore.isProduction()) // Active les fichiers versionnés en production (e.g. app.abc123.css)

    // Configure Babel pour le support ES6+ et les polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage'; // Polyfills uniquement pour ce qui est utilisé
        config.corejs = '3.38'; // Version de core-js utilisée
    })

    // Active le support de Sass/SCSS
    .enableSassLoader()

    // Active Stimulus (Symfony UX)
    .enableStimulusBridge('./assets/controllers.json')

    // Active le support de Vue.js
    .enableVueLoader()

    // Si vous utilisez React (décommentez cette ligne pour activer React)
    .enableReactPreset();

module.exports = Encore.getWebpackConfig();