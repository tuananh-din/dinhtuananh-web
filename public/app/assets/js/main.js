const readURL = (input) => {
    if (input.files && input.files[0]) {
      const reader = new FileReader()
      reader.onload = (e) => {
        $('#preview').attr('src', e.target.result)

        if ($(input).attr('name') === 'thumbnail') {
          const image = new Image()
          image.onload = () => {
            const ratio = image.naturalWidth / image.naturalHeight
            const isNearSixteenByNine = Math.abs(ratio - (16 / 9)) <= 0.08
            const warning = $('#thumbnail-aspect-warning')

            warning.prop('hidden', isNearSixteenByNine)
            warning.text(isNearSixteenByNine ? '' : 'Ảnh này không gần tỷ lệ 16:9; thumbnail có thể bị cắt khi hiển thị.')
          }
          image.src = e.target.result
        }

        if ($('#blog-image-aspect-warning').length) {
          const image = new Image()
          image.onload = () => {
            const ratio = image.naturalWidth / image.naturalHeight
            const isNearSixteenByNine = Math.abs(ratio - (16 / 9)) <= 0.08
            const warning = $('#blog-image-aspect-warning')

            warning.prop('hidden', isNearSixteenByNine)
            warning.text(isNearSixteenByNine ? '' : 'Ảnh này không gần tỷ lệ 16:9; ảnh đại diện bài viết có thể bị cắt khi hiển thị.')
          }
          image.src = e.target.result
        }
      }
      reader.readAsDataURL(input.files[0])
    }
  }
  $('.choose').on('change', function() {
      readURL(this)
    let i
    if ($(this).val().lastIndexOf('\\')) {
      i = $(this).val().lastIndexOf('\\') + 1
    } else {
      i = $(this).val().lastIndexOf('/') + 1
    }
    const fileName = $(this).val().slice(i)
    $('.label').text(fileName)
  })


  const readURL1 = (input) => {
    if (input.files && input.files[0]) {
      const reader = new FileReader()
      reader.onload = (e) => {
        $('#preview1').attr('src', e.target.result)
      }
      reader.readAsDataURL(input.files[0])
    }
  }
  $('.choose1').on('change', function() {
      readURL1(this)
    let i
    if ($(this).val().lastIndexOf('\\')) {
      i = $(this).val().lastIndexOf('\\') + 1
    } else {
      i = $(this).val().lastIndexOf('/') + 1
    }
    const fileName = $(this).val().slice(i)
    $('.label1').text(fileName)
  })

  $('input[name="avatar"]').on('change', function () {
    const file = this.files && this.files[0]
    const warning = $('#testimonial-avatar-aspect-warning')

    if (!file || !warning.length) return

    const reader = new FileReader()
    reader.onload = (e) => {
      const image = new Image()
      image.onload = () => {
        const isNearSquare = Math.abs((image.naturalWidth / image.naturalHeight) - 1) <= 0.08
        warning.prop('hidden', isNearSquare)
        warning.text(isNearSquare ? '' : 'Ảnh này không gần tỷ lệ vuông; avatar có thể bị cắt khi hiển thị.')
      }
      image.src = e.target.result
    }
    reader.readAsDataURL(file)
  })
