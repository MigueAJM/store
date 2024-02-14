class Exception extends Error
{
	constructor(message, type = 'Exception', code = 500){
		message = `Error(${code}) - ${message}.`;
		super(message);
		this.name = type;
		this.code = code;
		this.reportError();
	}
	
	reportError(){
		const origin = window.origin;
		const dev = ['localhost', '127.0.0.1'];
		let isDev = false;
		dev.forEach(i => {if(origin.includes(i)) isDev = true});
		if(isDev){
			console.log({error: this.message, type: this.name, code: this.code});
			return;
		}
		const now = new Date();
		_fetch.post(urlError, {
			body: JSON.stringify({
				user: _user.email ?? 'guest',
				platform: navigator.userAgent,
				date: now.toLocaleDateString('es-MX'),
				type: this.name,
				code: this.code,
				error: this.message
			}).then(res => {})
		});
	}
}