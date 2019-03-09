#!/bin/bash

# Check and skip if OpenCV4 is already installed.
`which pkg-config` --exists opencv
if [[ $? -eq 0 ]]; then
    echo "OpenCV $(pkg-config --modversion opencv) is already installed!"
    exit 0
fi

mkdir /usr/src/opencv-lib && cd /usr/src/opencv-lib

git clone --depth 1 --branch 3.4.5 https://github.com/opencv/opencv_contrib.git
git clone --depth 1 --branch 3.4.5 https://github.com/opencv/opencv.git && cd opencv

mkdir build && cd build

cmake -D CMAKE_BUILD_TYPE=RELEASE \
    -D CMAKE_INSTALL_PREFIX=/usr/local \
    -D WITH_TBB=ON \
    -D WITH_V4L=ON \
    -D INSTALL_C_EXAMPLES=OFF \
    -D INSTALL_PYTHON_EXAMPLES=OFF \
    -D BUILD_EXAMPLES=OFF \
    -D BUILD_JAVA=OFF \
    -D BUILD_TESTS=OFF \
    -D WITH_QT=ON \
    -D WITH_OPENGL=ON \
    -D OPENCV_GENERATE_PKGCONFIG=ON \
    -D OPENCV_EXTRA_MODULES_PATH=../../opencv_contrib/modules ..

make -j7
make install

sh -c 'echo "/usr/local/lib" > /etc/ld.so.conf.d/opencv.conf'
ldconfig

# Cleanup.
rm -rf /usr/src/opencv-lib
apt-get purge -y cmake && apt-get autoremove -y --purge
