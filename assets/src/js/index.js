import domReady from '@wordpress/dom-ready';
import './publicPath';
import { useNative, useNativeAll } from './dom';

window.InnocodeWPHybridLazyLoading = window.InnocodeWPHybridLazyLoading || {};
window.InnocodeWPHybridLazyLoading.useNative = useNative;
window.InnocodeWPHybridLazyLoading.useNativeAll = useNativeAll;

domReady(async () => {
  const { lazyEnqueueLazysizes } = window.innocodeWPHybridLazyLoadingConfig;
  const hasImageLoadingSupport = 'loading' in HTMLImageElement.prototype;
  const hasIFrameLoadingSupport = 'loading' in HTMLIFrameElement.prototype;

  if (hasImageLoadingSupport) {
    useNativeAll(document.querySelectorAll('img.lazyload'));
  }

  if (hasIFrameLoadingSupport) {
    useNativeAll(document.querySelectorAll('iframe.lazyload'));
  }

  document.addEventListener('lazybeforeunveil', (event) => {
    if ('loading' in event.target) {
      useNative(event.target);
    }
  });

  if (lazyEnqueueLazysizes) {
    if (!hasImageLoadingSupport || !hasIFrameLoadingSupport) {
      await import('lazysizes');
    }
  } else {
    await import('lazysizes');
    await import('lazysizes/plugins/native-loading/ls.native-loading');
  }
});
