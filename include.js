document.addEventListener("DOMContentLoaded",function()
{
	var main_language = "en",
	extra_languages = ["de"],
	language_to_use, user_languages = [],
	domain = location.hostname,
	arr = location.hostname.split(".").reverse();
	if(arr.length > 1)
	{
		domain = arr[1] + "." + arr[0];
	}
	if("languages" in navigator)
	{
		navigator.languages.forEach(function(lang)
		{
			user_languages.push(lang.toLowerCase().substr(0, 2));
		});
	}
	if("language" in navigator)
	{
		user_languages.push(navigator.language.toLowerCase().substr(0, 2));
	}
	user_languages.forEach(function(lang)
	{
		if(!language_to_use && (lang == main_language || extra_languages.indexOf(lang) > -1))
		{
			language_to_use = lang;
		}
	});
	if(language_to_use)
	{
		if(language_to_use != main_language && location.host == domain)
		{
			location.host = language_to_use + "." + domain;
			return;
		}
		else if(language_to_use == main_language && location.host != domain)
		{
			location.host = domain;
			return;
		}
	}
	// ...
});
