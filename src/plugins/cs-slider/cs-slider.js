$(function () {
  initCSSlider();
});

const csSliders = document.querySelectorAll('.cs-slider');
let csSliderImages = [];
let csSliderImagesCount = [];

const csDot = index => `<a href="javascript:void(0)" data-image="${index}"></a>`;

const csThumb = (index, img) => `
  <a href="javascript:void(0)" data-image="${index}">
    <img src="${img}">
  </a>
`;

const initCSSlider = () => csSliders.forEach((csSlider, index) => {
  const imagesContainer = $(csSlider).children('.imgs');
  const images = $(csSlider).children('.imgs').children('img');
  const totalImages = images.length;

  const dotsContainer = $(csSlider).children('.dots');
  const thumbsContainer = $(csSlider).children('.thumbs');

  csSliderImages[index] = images;
  csSliderImagesCount[index] = totalImages;

  images.each(imageIndex => {
    const image = $(images[imageIndex]).attr('src');
    dotsContainer.append(csDot(imageIndex))

    if (imageIndex < 4) thumbsContainer.append(csThumb(imageIndex, image));
  });

  const dots = $(csSlider).children('.dots').children('a');

  $(images[0]).addClass('active');
  $(dots[0]).addClass('active');

  $('.dots a').on('click', function (e) {
    e.stopPropagation();
    const SLIDER_WIDTH = $(csSlider).width();
    const imageIndex = $(this).attr('data-image');

    const newPosition = SLIDER_WIDTH * imageIndex;

    console.log(newPosition);

    $(imagesContainer).animate({
      scrollLeft: newPosition
    }, 500);

    //$(images).removeClass('active');
    $(dots).removeClass('active');
    $(dots[imageIndex]).addClass('active');

    //$(images[imageIndex]).addClass('active');    
  });

  $('.thumbs a').on('click', function (e) {
    e.stopPropagation();
    const SLIDER_WIDTH = $(csSlider).width();
    const imageIndex = $(this).attr('data-image');

    const newPosition = SLIDER_WIDTH * imageIndex;

    console.log(newPosition);

    $(imagesContainer).animate({
      scrollLeft: newPosition
    }, 500);

    //$(images).removeClass('active');
    $(dots).removeClass('active');
    $(dots[imageIndex]).addClass('active');

    //$(images[imageIndex]).addClass('active');    
  });

  // Dar animacion
  const hasAnimation = $(csSlider).hasClass('animation');

  if (hasAnimation) {
    const dataInterval = $(csSlider).attr('data-interval');
    const perImages = $(csSlider).attr('data-per-image');

    const interval = dataInterval == undefined ? 3000 : dataInterval;
    const perImage = perImages == undefined ? 1 : perImages;
    const DEVICE_WIDTH = window.screen.width;

    let imageIndexAnimated = DEVICE_WIDTH >= 1024 ? perImage : 1;
    const SLIDER_WIDTH_ANIMATED = $(images[0]).width();

    console.log(SLIDER_WIDTH_ANIMATED)

    setInterval(() => {
      const newPosition = SLIDER_WIDTH_ANIMATED * imageIndexAnimated;

      const animationTime = perImage > 1 ? 1500 : 1000;

      $(imagesContainer).animate({
        scrollLeft: newPosition
      }, animationTime);

      $(dots).removeClass('active');
      $(dots[imageIndexAnimated]).addClass('active');


      if ((imageIndexAnimated + 1) >= totalImages) imageIndexAnimated = 0;
      else {
        if (DEVICE_WIDTH >= 1024) imageIndexAnimated = imageIndexAnimated + perImage;
        else imageIndexAnimated = imageIndexAnimated + 1;
      }
    }, interval);
  }
});