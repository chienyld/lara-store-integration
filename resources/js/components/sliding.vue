<template>
  <div class="wrapper">
    <h2>Autoplay</h2>
    <div class="description">
    </div>
    <splide :options="options" has-slider-wrapper>
      <splide-slide v-for="slide in slides" :key="slide.src">
        <img :src="slide.src" alt="slide.alt">
      </splide-slide>
      <template v-slot:controls>
        <div class="splide__progress">
          <div class="splide__progress__bar">
          </div>
        </div>
        <div class="splide__autoplay">
          <button class="splide__play">Play</button>
          <button class="splide__pause">Pause</button>
        </div>
      </template>
    </splide>
  </div>
</template>
<script>
  import { Splide, SplideSlide } from '@splidejs/vue-splide';
  //import { createSlides } from "@splidejs/vue-splide";
  export default {
  	components: {
  		Splide,
      SplideSlide,
    },
	  data() {
		  return {
			  options: {
				  rewind      : true,
				  perPage     : 1,
				  gap         : '1rem',
				  autoplay    : true,
				  pauseOnHover: false,
          arrows      : 'slider',
			  },
			  slides: createSlides(),
		  };
	  },
  }
</script>
<style scoped lang="scss">
  $color: #20b2aa;
  .splide {
    &__autoplay {
      margin-top: 1em;
      text-align: center;
    }
    &__play, &__pause {
      border: none;
      background: $color;
      color: white;
      transition: background-color .2s linear;
      cursor: pointer;
      padding: .3em 1em;
      border-radius: 2em;
      outline: none;
      &:hover {
        background: darken( $color, 20% );
      }
    }
  }
</style>