PHP_INCLUDE = `php-config --includes`
PHP_LIBS = `php-config --libs`
PHP_LDFLAGS = `php-config --ldflags`
PHP_INCLUDE_DIR = `php-config --include-dir`
PHP_EXTENSION_DIR = `php-config --extension-dir`
GTK_FLAGS = `pkg-config --cflags --libs gtk+-3.0`

gtk.so: gtk.cc
	c++ -DHAVE_CONFIG_H -o gtk.so -g -O2 -fPIC -shared gtk.cc -std=c++11 ${PHP_INCLUDE} -I${PHP_INCLUDE_DIR} -lphpx\
	 ${GTK_FLAGS}
install: gtk.so
	cp gtk.so ${PHP_EXTENSION_DIR}/
clean:
	rm *.so
