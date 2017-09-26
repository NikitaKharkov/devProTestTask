Dev Pro test task
===============

# Task:
###  Write the parser of the site http://www.wordreference.com/synonyms/
 
- a) use these words for search https://raw.githubusercontent.com/dwyl/english-words/master/words.txt
- b) parse pages to files
- c) parse files as html to mongodb collection where the key will be the search word, the value will be all content from .content div#article

Use OOP and PHP 7.1

# Setup

Download project and go to project directory.
Run `docker-compose up` and then `docker exec -it dp_php bash`.

Then you have run `composer install` and then `php bin/console rabbitmq-supervisor:rebuild`.

Run `exit` to out of container.

Add to `/etc/hosts` of your machine `dp.loc` and then in browser write just domain `dp.loc`.
That's it. 

# Results

I used proxy servers and it helps but slow process very much.
I think there is a much easier solution to make it, but I don't know it for now...

I use rabbitMQ queries to speed up the process, but I faced with captcha and I don't think now.
And then I decided to use proxies. After many attempts I discovered that even if I use proxy
I can't provision of grab synonyms of all words. So I see 3 ways to solve problem:

- Use so many proxies and if we find that some proxy return content with captcha - remove from list
and that's it;
- Compute what time is acceptable for grabbing and set timeout. But its so slow way;
- Use API for this as planned by this site.

If you could tell me how to do it I will be very thankful because I want to decide this task
very much anyway. Not depends on your decision.
