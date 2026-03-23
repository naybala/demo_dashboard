import { usePage } from "@inertiajs/vue3";

/**
 * Translation helper for Vue 3 components.
 * Accesses translations shared via Inertia in page.props.translations.
 *
 * @param {string} key - Translation key (e.g., 'category.name')
 * @param {string} defaultVal - Default value if key is not found
 * @returns {string} - Translated string or key/defaultVal
 */
export const __ = (key, defaultVal = "") => {
  const page = usePage();

  if (!page || !page.props || !page.props.translations) {
    return defaultVal || key;
  }

  const parts = key.split(".");
  let result = page.props.translations;

  for (const part of parts) {
    if (result && result[part]) {
      result = result[part];
    } else {
      return defaultVal || key;
    }
  }
  return result;
};

/**
 * Format a number/string with thousand separators.
 * @param {number|string} value
 * @param {number} decimals
 * @returns {string}
 */
export const formatNumber = (value, decimals = 0) => {
  if (value === "" || value === null || value === undefined) return "";
  const num =
    typeof value === "string" ? parseFloat(value.replace(/,/g, "")) : value;
  if (isNaN(num)) return value;

  return num.toLocaleString("en-US", {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  });
};

/**
 * Strip non-numeric characters (except decimal point) from a formatted string.
 * @param {string} value
 * @returns {number|string}
 */
export const unformatNumber = (value) => {
  if (!value && value !== 0) return "";
  const raw = value.toString().replace(/[^0-9.]/g, "");
  return raw === "" ? "" : parseFloat(raw);
};
