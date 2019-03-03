name = shtfy
registry = docker.gerade.org

build:
	docker build -t $(registry)/$(name) .

push: build
	docker push $(registry)/$(name)

run: build
	docker run --rm -it --name $(name) \
	           -p 8080:8080 \
	           $(registry)/$(name)
