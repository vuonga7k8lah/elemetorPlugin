window.addEventListener('elementor/init', () => {
    (function ($) {
        let sliderControl = elementor.modules.controls.BaseData.extend({

            onReady: function () {
                let output = $(".wil-slider-input");
                let slider = $(".wil-slider-range");
                console.log(1,output);
                console.log(2,slider);
                this.inputSlider = slider.val();
                output.innerHTML = slider.val();

                slider.oninput = function () {
                    console.log(this.value);
                    console.log("xxx")
                    output.innerHTML = this.value;
                }
                slider.onchange = function () {
                    console.log(this.value);
                    console.log("xxx")
                    output.innerHTML = this.value;
                }
                //
                slider.on('input change', () => {
                    output.innerHTML = this.value;
                    this.saveValue();
                })

            },

            saveValue: function () {
                this.setValue(this.inputSlider.val());
            },

            onBeforeDestroy: function () {

                //this.saveValue();

            }

        });
        elementor.addControlView('wil-slider', sliderControl);

    })(jQuery)
});
