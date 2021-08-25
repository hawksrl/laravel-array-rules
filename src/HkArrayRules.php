<?php

namespace Hawk\ArrayRules;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HkArrayRules
{
    /**
     * Convierte reglas escritas en forma de array, a la forma que espera Laravel.
     * ```
     *
     * $arrayRules = [
     *   'id' => [
     *      'required',
     *      'exists:table,column',
     *   ],
     *   'articles' => [
     *      'data' => [
     *          '*' => [
     *              'id' => [
     *                  'required',
     *                  'exists:articles,id',
     *              ],
     *          ],
     *       ],
     *    ],
     * ];
     *
     * // Produce:
     *
     * $rules = [
     *   'id' => 'required|exists:table,column',
     *   'articles.data.*.id' => 'required|exists:articles,id',
     * ];
     *
     * ```
     */
    public static function parseRules(array $rules): array
    {
        $rules = Arr::dot($rules);

        if (! count($rules)) {
            return [];
        }

        $output = [];

        foreach (Arr::dot($rules) as $key => $value) {
            if ($value == null) {
                continue;
            }

            $keyLastDotPos = strrpos($key, '.');

            $rule = substr($key, $keyLastDotPos + 1);

            $key = substr($key, 0, $keyLastDotPos);

            if (is_numeric($rule)) {
                $rule = $value;
            }

            if (! isset($output[$key])) {
                $output[$key] = $rule;
            } else {
                $output[$key] .= '|' . $rule;
            }
        }

        return $output;
    }

    /**
     * Traduce cada mensaje de error.
     *
     * @param $rules
     * @return array
     */
    public static function parseMessages(array $rules): array
    {
        $rules = Arr::dot($rules);

        if (! count($rules)) {
            return [];
        }

        $output = [];

        foreach (Arr::dot($rules) as $key => $message) {
            $key = Str::before($key, ':');
            $output["{$key}"] = $message;
        }

        return $output;
    }
}
