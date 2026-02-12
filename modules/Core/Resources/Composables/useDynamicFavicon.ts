export function useDynamicFavicon(): void {
  const appName = useProperty<string>('app.name');
  let scheduledFrameId: number | null = null;

  const updateFavicon = (): void => {
    const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--ui-primary').trim();
    const initial = appName.value?.charAt(0).toUpperCase() ?? 'S';

    const svg = [
      `<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'>`,
      `<rect width='128' height='128' rx='24' fill='${primaryColor}'/>`,
      `<text x='64' y='64' fill='white' font-family='system-ui,-apple-system,sans-serif'`,
      ` font-size='64' font-weight='700' text-anchor='middle' dominant-baseline='central'>${initial}</text>`,
      `</svg>`,
    ].join('');

    const faviconHref = `data:image/svg+xml,${encodeURIComponent(svg)}`;

    document.querySelectorAll<HTMLLinkElement>('link[rel="icon"]')
      .forEach((existingFaviconLink: HTMLLinkElement) => existingFaviconLink.remove());

    const faviconLink = document.createElement('link');
    faviconLink.rel = 'icon';
    faviconLink.type = 'image/svg+xml';
    document.head.appendChild(faviconLink);
    faviconLink.href = faviconHref;
  };

  const scheduleUpdate = (): void => {
    if (scheduledFrameId !== null) {
      return;
    }

    scheduledFrameId = requestAnimationFrame(() => {
      scheduledFrameId = null;
      updateFavicon();
    });
  };

  onMounted(() => {
    updateFavicon();

    const rootObserver = new MutationObserver(scheduleUpdate);
    rootObserver.observe(document.documentElement, {
      attributeFilter: ['class', 'style'],
      attributes: true,
    });

    const headObserver = new MutationObserver(scheduleUpdate);
    headObserver.observe(document.head, {
      characterData: true,
      childList: true,
      subtree: true,
    });

    onUnmounted(() => {
      rootObserver.disconnect();
      headObserver.disconnect();

      if (scheduledFrameId !== null) {
        cancelAnimationFrame(scheduledFrameId);
      }
    });
  });

  watch(appName, updateFavicon);
}
