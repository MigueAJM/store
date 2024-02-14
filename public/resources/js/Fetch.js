class Fetch
{
	// application/x-www-form-urlencoded;charset=UTF-8
	options = {
		headers: {
			'Content-Type': "application/json;charset=UTF-8"
		}
	}
	constructor(){};

	/**
	 * @param {string} url
	 * @param {object} options
	 * @returns {Promise<Response|Exception>}
	 */
	async _fetch(url, options){
		const controller = new AbortController();
		const signal = controller.signal;
		const timeout = 6000;
		if(!options.hasOwnProperty('headers')) options.headers = this.options.headers;
		options.signal = signal;
		const timeoutId = setTimeout(() => signal.aborted(), timeout);
		try {
			const response = await fetch(url, options);
			const contentType = response.headers.get('Content-Type');
			const isJson = contentType.includes('application/json');
			if(!response.ok){
				let error = "";
				if(isJson){
					const json = await response.json();
					error = json.error ?? response.statusText;
				}
				if(contentType.includes('text/html') || contentType.includes('text/plain')){
					const text = await response.text();
					error = text;
				}
				return new Exception(error, response.statusText, response.status);
			}
			if(isJson) return await response.json();
			if(contentType.includes('text/html')) return await response.text();
			return await response.blob();
		} catch (error) {
			if(error.status === 20){
				return new Exception("Timeout error.", "Network connect timeout error", 599);
			}
			return new Exception(
				error.message ?? "Internal server error.",
				error.name ?? "Interval server error",
				error.code ?? 500
			);
		} finally {
			clearTimeout(timeoutId);
		}
	}

	/**
	 * @param {string} url
	 * @param {object} options
	 * @returns {Promise<Response|Exception>}
	 */
	async get(url, options = {}){
		options.method = "GET";
		return await this._fetch(url, options);
	}

	/**
	 * @param {string} url
	 * @param {object} options
	 * @returns {Promise<Response|Exception>}
	 */
	async post(url, options = {}){
		options.method = "POST";
		return await this._fetch(url, options);
	}

	/**
	 * @param {string} url
	 * @param {object} options
	 * @returns {Promise<Response|Exception>}
	 */
	async put(url, options = {}){
		options.method = "PUT";
		return await this._fetch(url, options);
	}

	/**
	 * @param {string} url
	 * @param {object} options
	 * @returns {Promise<Response|Exception>}
	 */
	async delete(url, options = {}){
		options.method = "DELETE";
		return await this._fetch(url, options);
	}
}