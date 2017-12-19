$().ready(function () {

    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        }
    });

    $("#signupForm").validate({
        rules: {
            email: {
                required: true,
                remote: {
                    url: '/check/email',
                    type: "post",
                    data: {
                            email: $('#signupForm:input[name="email"]').val()
                        }
                },
                email: true,
                maxlength: 100
            },
            password: {
                required: true,
                alphanumeric: true,
                minlength: 6
            },
            passwordAgain: {
                equalTo: '#signupPass'
            }
        },
        messages: {
            email: {
                remote: 'Такой Email уже занят, попробуйте войти'
            },
            password: {
                alphanumeric: 'Пароль может содержать только буквы и числа'
            }
        }
    });

    $("#profileForm").validate({
        rules: {
            f_name: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            l_name: {
                required: true,
                minlength: 2,
                maxlength: 40
            },
            email: {
                required: true,
                email: true,
                maxlength: 100,
                remote: {
                    url: '/check/email',
                    type: "post",
                    data: {
                        email: $('#profileForm:input[name="email"]').val()
                    }
                },
            },
            phone_number: {
                pattern: '\\+380\\d{9}',
                remote: {
                    url: '/check/phone',
                    type: "post",
                    data: {
                        phone: $('#profileForm:input[name="phone_number"]').val()
                    }
                }
            },
            city : {
                maxlength: 64
            }
        },
        messages: {
            phone_number: {
                pattern: 'Введите корректный номер телефона',
                remote: 'Такой номер телефона уже используеться'
            },
            email: {
                remote: 'Такой Email уже занят'
            }
        }
    });

    $("#changePassForm").validate({
        rules: {
            password: {
                required: true,
                alphanumeric: true,
                minlength: 6
            },
            passwordAgain: {
                equalTo: '#inputPassword-ChangePassword'
            }
        }
    });

    $("#addReviewForm").validate({
        rules: {
            text: {
                required: true
            }
        },
        messages: {
            mark: {
                required: 'Выберите марку авто'
            },
            model: {
                required: 'Выберите модель авто'
            },
            text: {
                required: 'Напишите отзыв об автомобиле'
            }
        }
    });

    $("#addTheme").validate({
        rules: {
            title: {
                required: true,
                minlength: 2,
                remote: {
                    url: '/check/themeTitle',
                    type: "post",
                    data: {
                        title: $('#addTheme:input[name="title"]').val()
                    }
                }
            },
            text: {
                required: true
            }
        },
        messages: {
            title: {
                remote: 'Такая тема уже существует'
            }
        }
    });

    $("#createArticle").validate({
        rules: {
            title: {
                required: true,
                minlength: 3,
                maxlength: 200,
                remote: {
                    url: '/check/articleTitle',
                    type: "post",
                    data: {
                        title: $('#createArticle:input[name="title"]').val()
                    }
                }
            },
            description: {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            image: {
                accept: "image/*",
                required: true
            },
            text: {
                required: true
            }
        },
        messages: {
            title: {
                remote: 'Такая статья уже существует'
            },
            image: {
                accept: 'Выберите картинку',
                required: 'Необходимо выбрать файл'
            }
        }
    });
    $("#updateArticle").validate({
        rules: {
            title: {
                required: true,
                minlength: 3,
                maxlength: 200
            },
            description: {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            image: {
                accept: "image/*"
            },
            text: {
                required: true
            }
        },
        messages: {
            image: {
                accept: 'Выберите картинку'
            }
        }
    });
    $("#createMark").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
                remote: {
                    url: '/check/mark',
                    type: "post",
                    data: {
                        name: $('#createMark:input[name="name"]').val()
                    }
                }
            },
            description_page: {
                maxlength: 255
            },
            description: {
                required: true
            },
            logo: {
                required: true,
                accept: "png"
            }
        },
        messages: {
            logo: {
                accept: 'Выберите картинку в формате png'
            },
            name: {
                remote: 'Такая марка уже существует'
            }
        }
    });
    $("#updateMark").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50
            },
            description_page: {
                maxlength: 255
            },
            description: {
                required: true
            },
            logo: {
                accept: "png"
            }
        },
        messages: {
            logo: {
                accept: 'Выберите картинку в формате png'
            }
        }
    });
    $("#createCar").validate({
        rules: {
            model: {
                required: true,
                minlength: 2,
                maxlength: 50,
                remote: {
                    url: '/check/car',
                    type: "post",
                    data: {
                        model: $('#createCar:input[name="model"]').val()
                    }
                }
            },
            text: {
                required: true
            },
            icon: {
                required: true,
                accept: "png"
            },
            img: {
                required: true,
                accept: "image/*"
            },
            brand_id: {
                required: true
            }
        },
        messages: {
            model: {
                remote: 'Такое авто уже существует'
            },
            icon: {
                accept: 'Выберите картинку в формате png'
            },
            img: {
                accept: 'Выберите картинку'
            },
            brand_id: {
                required: 'Выберите марку для авто'
            }
        }
    });
    $("#formCreateCategory").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
                minlength: 2,
                remote: {
                    url: '/check/category',
                    type: "post",
                    data: {
                        name: $('#formCreateCategory:input[name="name"]').val()
                    }
                }
            },
            description_page: {
                maxlength: 255
            }
        },
        messages: {
            name: {
                remote: 'Такая категория уже существует'
            }
        }
    });
    $("#updateCar").validate({
        rules: {
            model: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            text: {
                required: true
            },
            icon: {
                accept: "png"
            },
            img: {
                accept: "image/*"
            },
            brand_id: {
                required: true
            }
        },
        messages: {
            icon: {
                accept: 'Выберите картинку в формате png'
            },
            img: {
                accept: 'Выберите картинку'
            },
            brand_id: {
                required: 'Выберите марку для авто'
            }
        }
    });
});
