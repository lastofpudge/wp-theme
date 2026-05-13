<?php
/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Translated;

use PLL_Language;
use WP_REST_Request;
use WP_REST_Server;

/**
 * Trait for handling batch request language context.
 *
 * Provides shared functionality to maintain language context during WooCommerce batch requests, where the request language detection doesn't work.
 *
 * This trait currently handles only 'create' operations. Update and delete operations don't require the language queue:
 * Create: Product/term doesn't exist yet, no language, queue needed.
 * Update: Product/term exists, language already assigned, queue not needed.
 * Delete: No language validation required, queue not needed.
 *
 * @since 2.2
 */
trait Batch {
	/**
	 * Queue of languages for batch items being processed.
	 *
	 * @var string[]
	 */
	private $batch_lang_queue = array();

	/**
	 * Initializes the language queue from a batch request.
	 * Extracts language codes from 'create' operations and stores them in a FIFO queue.
	 *
	 * @since 2.2
	 *
	 * @param mixed           $result  Response to replace the requested version with.
	 * @param WP_REST_Server  $server  Server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 * @return mixed Unchanged result.
	 *
	 * @phpstan-param WP_REST_Request<array> $request
	 */
	public function init_batch_lang_queue( $result, $server, $request ) {
		$route = $request->get_route();

		// Check if this is a batch request that this handler should process.
		if ( ! $this->is_batch_route( $route ) ) {
			return $result;
		}

		$items = array_filter( $request->get_params() );
		if ( empty( $items['create'] ) ) {
			return $result;
		}

		$this->batch_lang_queue = array();
		foreach ( $items['create'] as $item ) {
			$this->batch_lang_queue[] = $item['lang'] ?? '';
		}

		return $result;
	}

	/**
	 * Moves to the next batch item.
	 *
	 * @since 2.2
	 *
	 * @return void
	 */
	public function next_batch_item(): void {
		if ( ! empty( $this->batch_lang_queue ) ) {
			array_shift( $this->batch_lang_queue );
		}
	}

	/**
	 * Returns the language to use when searching if a SKU is unique.
	 *
	 * @since 1.3
	 * @since 2.2 Moved from REST\Translated\Product and added support for batch requests.
	 *
	 * @param PLL_Language|false $language Found language object, `false` if none.
	 * @return PLL_Language|false The requested language object, `false` if none.
	 */
	public function filter_language_with_request( $language ) {
		// For non-batch requests, use the request language.
		if ( empty( $this->batch_lang_queue ) ) {
			return $this->request->get_language() ?? $language;
		}

		// For batch requests, use the language from the queue.
		return $this->get_batch_language() ?? $language;
	}

	/**
	 * Gets the current batch language.
	 *
	 * @since 2.2
	 *
	 * @return PLL_Language|null The language object for the current batch item, or null if not in a batch.
	 */
	protected function get_batch_language(): ?PLL_Language {
		if ( empty( $this->batch_lang_queue[0] ) ) {
			return null;
		}

		$language = $this->model->get_language( $this->batch_lang_queue[0] );
		if ( $language instanceof PLL_Language ) {
			return $language;
		}

		return null;
	}
}
