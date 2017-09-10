// -----------------------------------------------------------------------------
// Document Ready (Not working)
// -----------------------------------------------------------------------------
(() => {
  const canvas = window.$( '#error-page-canvas' );

  const context = canvas.getContext( '2d' );
  const img = new Image();
  let w;
  let h;
  let glitchInterval;
  let offset;

  const init = () => {
    clearInterval( glitchInterval );
    canvas.width = w = window.innerWidth;
    offset = w * 0.1;

    canvas.height = h = ~~(650 * ((w - (offset * 2)) / img.width));

    glitchInterval = setInterval( () => {
      clear();
      context.drawImage( img, 0, 0, img.width, 750, offset, 0, w - (offset * 2),
        h );
      setTimeout( glitchImg, randInt( 250, 1000 ) );
    }, 500 );
  };

  const clear = () => {
    context.rect( 0, 0, w, h );
    context.fill();
  };

  const glitchImg = () => {
    for ( let i = 0; i < randInt( 1, 13 ); i++ ) {
      const x = Math.random() * w;
      const y = Math.random() * h;
      const spliceWidth = w - x;
      const spliceHeight = randInt( 5, h / 3 );

      context.drawImage( canvas, 0, y, spliceWidth, spliceHeight, x, y,
        spliceWidth, spliceHeight );
      context.drawImage( canvas, spliceWidth, y, x, spliceHeight, 0, y, x,
        spliceHeight );
    }
  };

  const randInt = ( a, b ) => ~~(Math.random() * (b - a) + a);

  img.onload = () => {
    init();
    window.onresize = init;
  };

  img.src = `${base_url}images/logo/logo-full.svg`;
  console.log( img.src );

});
