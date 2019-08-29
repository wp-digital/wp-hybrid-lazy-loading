export const useNative = element => {
  const { forceUseLazysizes } = window.innocodeWPHybridLazyLoadingConfig;

  if (element.dataset.src) {
    element.setAttribute('src', element.dataset.src);
  }

  if (element.dataset.srcset) {
    element.setAttribute('srcset', element.dataset.srcset);
  }

  if (element.dataset.sizes) {
    element.setAttribute('sizes', element.dataset.sizes);
  }

  if (!forceUseLazysizes) {
    element.classList.remove('lazyload');
  }
};

export const useNativeAll = elements =>
  elements.forEach(element => useNative(element));
