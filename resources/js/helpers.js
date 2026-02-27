import { get } from "svelte/store";
import { page } from "@inertiajs/svelte";

/**
 * Translation helper for Svelte components.
 * Accesses translations shared via Inertia in $page.props.translations.
 *
 * @param {string} key - Translation key (e.g., 'category.name')
 * @param {string} defaultVal - Default value if key is not found
 * @returns {string} - Translated string or key/defaultVal
 */
export const __ = (key, defaultVal = "") => {
  const $page = get(page);
  const parts = key.split(".");
  let result = $page.props.translations;

  for (const part of parts) {
    if (result && result[part]) {
      result = result[part];
    } else {
      return defaultVal || key;
    }
  }
  return result;
};
