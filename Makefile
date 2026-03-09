

PadelClock.html: PadelClock.php
	php $< >$@


push:
	git add .
	git commit -m update
	git push
