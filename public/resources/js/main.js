//require("./Fetch.js");
//import { Exception } from "./Excepction.js";
document.addEventListener('DOMContentLoaded', () => {
	if(_formId === undefined) return;
	const form = document.querySelector('form#'+_formId?.signIn ?? '');
	if(!form) return;
	form.addEventListener("submit", async e => {
		e.preventDefault();
		const body = new URLSearchParams(new FormData(e.target));
		const res = await _fetch[form.method](form.action, {
			body,
			headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'}
		});
		if(res instanceof Exception){
			return  alert(res.message);
		}
		alert('success');
	})
});