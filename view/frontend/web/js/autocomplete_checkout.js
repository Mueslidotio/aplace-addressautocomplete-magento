define([
    'jquery',
], function ($) {
    'use strict';
    function waitForElm(selector) {
        return new Promise(resolve => {
            if (document.querySelector(selector)) {
                return resolve(document.querySelector(selector));
            }

            const observer = new MutationObserver(mutations => {
                if (document.querySelector(selector)) {
                    resolve(document.querySelector(selector));
                    observer.disconnect();
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    }

    return function initAutocomplete(config) {
        const aplaceAutocompleteManager = () => {
            if (!config) { return }
            const aplace_autocomplete_field_address = config.aplace_autocomplete_field_address;
            const aplace_autocomplete_field_city = config.aplace_autocomplete_field_city;
            const aplace_autocomplete_field_postcode = config.aplace_autocomplete_field_postcode;
            const aplace_autocomplete_field_country = config.aplace_autocomplete_field_country;
            const aplace_autocomplete_field_region = config.aplace_autocomplete_field_region;

            const inputNames = {};
            inputNames[aplace_autocomplete_field_address] = { 'type': 'address' };
            inputNames[aplace_autocomplete_field_city] = { 'type': 'city' };
            inputNames[aplace_autocomplete_field_postcode] = { 'type': 'postcode' };

            const callAPlaceAutocomplete = () => {
                let countries = '';
                const selectElement = document.querySelector('[name="' + aplace_autocomplete_field_country + '"]');
                if (selectElement) {
                    countries = Array.from(selectElement.options)?.map(option => option.value.toLowerCase()).filter(option => option)?.join(',');
                }
                const instance = new APlaceAutocomplete({
                    autoFill: true,
                    useIcons: false,
                    blinkWhenFilled: true,
                    useDefaultStyle: false,
                    inputNames,
                    countries,
                });
                instance.setResultSelectedCallback((result) => {
                    const countryValueToTest = { text: ['country'], value: ['country_code'] };
                    const selectCountry = document.querySelector('[name="' + aplace_autocomplete_field_country + '"]');
                    if (selectCountry) {
                        selectCountryLoop:
                        for (const countryOption of selectCountry.options) {
                            for (const countryValType in countryValueToTest) {
                                for (const countryKey of countryValueToTest[countryValType]) {
                                    if (result.address.country_code) {
                                        if (countryOption[countryValType].toLowerCase() === result.address[countryKey].toLowerCase()) {
                                            selectCountry.value = result.address.country_code;
                                            selectCountry.dispatchEvent(new Event("change"));
                                            setTimeout(() => {
                                                const regionValueToTest = { text: ['region', 'state'], value: ['state_code', 'region_code'] }

                                                const selectRegion = document.querySelector('[name="' + aplace_autocomplete_field_region + '"]');
                                                if (selectRegion) {
                                                    selectRegionLoop:
                                                    for (const regionOption of selectRegion.options) {
                                                        for (const regionValType in regionValueToTest) {
                                                            for (const regionKey of regionValueToTest[regionValType]) {
                                                                if (result.address[regionKey]) {
                                                                    if (regionOption[regionValType].toLowerCase() === result.address[regionKey].toLowerCase()) {
                                                                        selectRegion.value = regionOption.value;
                                                                        selectRegion.dispatchEvent(new Event("change"));
                                                                        break selectRegionLoop;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }, 16);
                                            break selectCountryLoop;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    for (const itemType in instance.items) {
                        setTimeout(() => {
                            const item = instance.items[itemType];
                            item.input?.dispatchEvent(new Event("change"));
                        }, 300);
                    }
                });
            };

            let triesAPlaceAutocompleteCall = 0;
            const interval = setInterval(() => {
                try {
                    if (APlaceAutocomplete) {
                        waitForElm('[name="' + aplace_autocomplete_field_address + '"]').then(() => {
                            setTimeout(() => {
                                callAPlaceAutocomplete();
                            }, 16);
                        });
                        clearInterval(interval);
                    }
                } catch (e) { }
                triesAPlaceAutocompleteCall++;
                if (triesAPlaceAutocompleteCall > 20) {
                    clearInterval(interval);
                }
            }, 200);
        };
        $(document).ready(() => {
            aplaceAutocompleteManager();
        });
    }
});
