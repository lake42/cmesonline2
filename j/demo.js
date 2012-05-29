// select the thumbnails and make them trigger our overlay 
$("#thumbnails a").overlay({ 
 
    // each trigger uses the same overlay with the id "gallery" 
    target: '#gallery', 
 
    // optional exposing effect 
    expose: '#f1f1f1' 
 
// let the gallery plugin do its magic! 
}).gallery({ 
 
    // the plugin accepts its own set of configuration options 
    speed: 800 
});