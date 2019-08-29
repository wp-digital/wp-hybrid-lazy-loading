import domReady from '@wordpress/dom-ready';
import './publicPath';
import { useNative, useNativeAll } from './dom';

window.InnocodeWPHybridLazyLoading = window.InnocodeWPHybridLazyLoading || {};
window.InnocodeWPHybridLazyLoading.useNative = useNative;
window.InnocodeWPHybridLazyLoading.useNativeAll = useNativeAll;

domReady(() => {
  const { lazyEnqueueLazysizes } = window.innocodeWPHybridLazyLoadingConfig;
  const hasImageLoadingSupport = 'loading' in HTMLImageElement.prototype;
  const hasIFrameLoadingSupport = 'loading' in HTMLIFrameElement.prototype;

  if (hasImageLoadingSupport) {
    useNativeAll(document.querySelectorAll('img.lazyload'));
  }

  if (hasIFrameLoadingSupport) {
    useNativeAll(document.querySelectorAll('iframe.lazyload'));
  }

  if (lazyEnqueueLazysizes) {
    if (!hasImageLoadingSupport || !hasIFrameLoadingSupport) {
      import('lazysizes');
    }
  } else {
    import('lazysizes');
    import('lazysizes/plugins/native-loading/ls.native-loading');
  }

  document.addEventListener('lazybeforeunveil', event => {
    if ('loading' in event.target) {
      useNative(event.target);
    }
  });
});
