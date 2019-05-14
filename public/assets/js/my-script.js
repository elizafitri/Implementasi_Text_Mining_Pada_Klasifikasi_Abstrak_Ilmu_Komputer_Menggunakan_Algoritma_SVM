$(document).ready(function(){
    var borang = $('#manajemen_borang').DataTable({
      columns : [
        { width : '2px' },
        { width : '100px' },
        { width : '50px' },
        { width : '50px' }       
    ], 
  
      "processing": true,
      "autoWidth"   : false,
      "ajax": 'borang/data_borang',
      stateSave: true,
      "order": [],
      fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
    }
    })
  
    $('#tambah_borang').on('submit', function(){
      var jenis_borang_id = $('#jenis_borang_id').val();
      var aspek_penilaian_id = $('#aspek_penilaian_id').val();
      var bobot = $('#bobot').val();
      var pertanyaan = $('#pertanyaan').val();
      
      //var opsi = [],
      //var opsi = $('#opsi').val();
      var opsi = $('input[name="opsi[]"]').map(function(){ 
        return this.value; 
      }).get();
  
      $.ajax({
        type: "POST",
        url: 'borang/tambah_borang',
        beforeSend :function() {
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          jenis_borang_id:jenis_borang_id,
          aspek_penilaian_id:aspek_penilaian_id,
          bobot:bobot,
          pertanyaan:pertanyaan,
  
          'opsi[]':opsi,
        },
        dataType: "JSON",
        success: function (data) {
          borang.ajax.reload(null, false);
          swal({
            type: "success",
            title: 'Tambah Borang',
            text: 'Anda berhasil menambah borang',
          })
  
          $('#tambah-borang').modal('hide');
  
        
          $('#jenis_borang_id').val('');
          $('#aspek_penilaian_id').val('');
          $('#bobot').val('');
          $('#pertanyaan').val('');
          $('#opsi').val('');
        }
      })
      return false;
    });
  
    $('#manajemen_borang').on('click','.detail-borang', function () {
      var id_borang = $(this).data('id_borang');
  
      $.ajax({
        type: "POST",
        url: 'borang/detail_borang',
        beforSend: function (){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {id_borang:id_borang},
        success: function (data){
          swal.close();
            $('#detail_borang_matkul').modal('show');
            $('#tampil_borang_matkul').html(data);
        }
      })
      return false;
    });
  
    $('#manajemen_borang').on('click', '.ubah-borang', function(){
      var id_borang = $(this).data('id_borang');
      var id_bor = $(this).data('id_bor');
  
      $.ajax({
        type: "POST",
        url: 'borang/update_borang',
        beforSend: function(){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          id_borang:id_borang,
          id_bor:id_bor
        },
        success: function (data) {
          swal.close();
          $('#ubah_borang').modal('show');
          $('#form_ubah_borang').html(data);
        }
      })
      return false;
    });
  });

  $(document).ready(function(){
    var jadwal = $('#manajemen_jadwal').DataTable({
      columns : [
        { width : '2px' },
        { width : '100px' },
        { width : '50px' },
        { width : '50px' },
        { width : '50px' },
        { width : '50px' }       
    ], 
  
      "processing": true,
      "autoWidth"   : false,
      "ajax": 'jadwal/data_jadwal',
      stateSave: true,
      "order": [],
      fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
    }
    })
  
    $('#tambah_jadwal').on('submit', function(){
      var mulai_audit = $('#mulai_audit').val();
      var selesai_audit = $('#selesai_audit').val();
      var semester_id = $('#semester_id').val();
      var tahun_akademik_id = $('#tahun_akademik_id').val();
      var jenis_audit_id = $('#jenis_audit_id').val();
  
      $.ajax({
        type: "POST",
        url: 'jadwal/tambah_jadwal',
        beforeSend :function() {
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          mulai_audit:mulai_audit,
          selesai_audit:selesai_audit,
          semester_id:semester_id,
          tahun_akademik_id:tahun_akademik_id,
          jenis_audit_id:jenis_audit_id,
        },
        dataType: "JSON",
        success: function (data) {
          jadwal.ajax.reload(null, false);
          swal({
            type: "success",
            title: 'Tambah Jadwal',
            text: 'Anda berhasil menambah jadwal',
          })
  
          $('#tambah-jadwal').modal('hide');
  
          $('#mulai_audit').val('');
          $('#selesai_audit').val('');
          $('#semester_id').val('');
          $('#tahun_akademik_id').val('');
          $('#jenis_audit_id').val('');
        }
      })
      return false;
    });
  
    $('#manajemen_jadwal').on('click', '.ubah-jadwal', function(){
      var id_jadwal = $(this).data('id_jadwal');
  
      $.ajax({
        type: "POST",
        url: 'jadwal/edit_jadwal',
        beforSend: function(){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          id_jadwal:id_jadwal
        },
        success: function (data) {
          swal.close();
          $('#ubah_jadwal').modal('show');
          $('#form_edit_jadwal').html(data);

          $('#editjadwal').on('submit', function(){
            var edit_mulai_audit = $('#edit_mulai_audit').val();
            var edit_selesai_audit = $('#edit_selesai_audit').val();
            var edit_semester_id = $('#edit_semester_id').val();
            var edit_tahun_akademik_id = $('#edit_tahun_akademik_id').val();
            var edit_jenis_audit_id = $('#edit_jenis_audit_id').val();

            var id_jadwal = $('#idjadwal').val();
            $.ajax({
              type: "POST",
              url: 'jadwal/update_jadwal',
              beforeSend: function(){
                swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  text: 'Proses Update Dataa',
                  buttons: false,
                  onOpen: () => {
                    swal.showLoading()
                  },
                })
              },
              
              data: {
                edit_mulai_audit:edit_mulai_audit,
                edit_selesai_audit:edit_selesai_audit,
                edit_semester_id:edit_semester_id,
                edit_tahun_akademik_id:edit_tahun_akademik_id,
                edit_jenis_audit_id:edit_jenis_audit_id,
                id_jadwal:id_jadwal
              },
              dataType: "JSON",
              success: function (data) {
                jadwal.ajax.reload(null, false);
                swal({
                  type: "success",
                  title: 'Ubah Jadwal',
                  text: 'Anda berhasil mengubah jadwal',
                })
        
                $('#ubah_jadwal').modal('hide');

                if(data.success == true){ // if true (1)
                  setTimeout(function(){// wait for 5 secs(2)
                       location.reload(); // then reload the page.(3)
                  }, 5000); 
               }
                
              } 
            })
            return false;
          });
        }
      });
    })

    $('#manajemen_jadwal').on('click', '.hapus-jadwal', function(){
      var id_jadwal = $(this).data('id_jadwal');
      swal({
        title: 'Konfirmasi',
        text: "Anda ingin hapus jadwal",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Tidak',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: 'jadwal/delete_jadwal',
            method: "POST",
            beforSend: function() {
              swal({
                title: 'Menunggu',
                html: 'Memproses data',
                onOpen: () => {
                  swal.showLoading()
                }
              })
            },
            data:{id_jadwal:id_jadwal},
            dataType: "JSON",
            success: function(data){
              swal(
                'Hapus',
                'Jadwal berhasil di hapus',
                'success'
              )
              jadwal.ajax.reload(null, false)
            }
          })
        } else if (result.dismiss === swal.DismissReason.cancel) {
            swal(
              'Batal',
              'Anda membatalkan menghapaus data',
              'error'
            )
          }
        })
      });

  });
  
  $(document).ready(function () {
    // ini adalah fungsi untuk mengambil data user dan di  incluce ke dalam datatable
    var user = $('#manajemen_user').DataTable({
      "processing": true,
      "autoWidth"   : true,
      "ajax": 'user/data_user',
      stateSave: true,
      "order": []
    })
  
    $('#tambah_user').on('submit', function () {
      var role_id = $('#role').val();
      var username = $('#username').val(); // diambil dari id nama yang ada diform modal
      var password = $('#password').val();

      // var prodi_id = $('#prodi_id').val();
      // var lab_id = $('#lab_id').val();
      
      var prodi_id = $('input[name="prodi_id"]').map(function(){ 
        return this.value; 
      }).get();

      var lab_id = $('input[name="lab_id"]').map(function(){ 
        return this.value; 
      }).get();
  
     $.ajax({
       type: "POST",
       url: 'user/tambah_user',
       beforeSend :function () {
         swal({
             title: 'Menunggu',
             html: 'Memproses data',
             onOpen: () => {
               swal.showLoading()
             }
           })
         },
       data: {
         role_id:role_id,
         username:username,
         password:password,


         'prodi_id':prodi_id,
         'lab_id':lab_id

        },
       dataType: "JSON",
       success: function (data) {
         user.ajax.reload(null, false);
         swal({
             type: 'success',
             title: 'Tambah User',
             text: 'Anda Berhasil Menambah User'
           })
           // bersihkan form pada modal
           $('#tambah_user').modal('hide');
           // tutup modal
           $('#role').val('');
           $('#username').val('');
           $('#password').val('');
           $('#prodi_id').val('');
           $('#lab_id').val('');
       }
     })
     return false;
   });
  
   $('#manajemen_user').on('click','.ubah-user', function () {
    // ambil element id pada saat klik ubah
    var id_user =  $(this).data('id_user');
  
    $.ajax({
      type: "POST",
      url: 'user/edit_user',
      beforeSend :function () {
        let timerInterval
        swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Dataa',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
      data: {id_user:id_user},
      success: function (data) {
        swal.close();
          $('#edit_user').modal('show');
          $('#form_edit_user').html(data);
  
          // proses untuk mengubah data
          $('#edituser').on('submit', function () {
            var editusername= $('#editusername').val(); // diambil dari id nama yang ada diform modal
            var editpassword = $('#editpassword').val(); // diambil dari id alamat yanag ada di form modal
            var id_user = $('#iduser').val(); //diambil dari id yang ada di form modal
            $.ajax({
              type: "POST",
              url: 'user/update_user',
              beforeSend :function () {
                swal({
                    title: 'Menunggu',
                    html: 'Memproses data',
                    text: 'Proses Update Dataa',
                    buttons: false,
                    onOpen: () => {
                      swal.showLoading()
                    },
                  })
                },
              data: {editusername:editusername,editpassword:editpassword,id_user:id_user}, // ambil datanya dari form yang ada di variabel
              dataType: "JSON",
              success: function (data) {
                user.ajax.reload(null,false);
                swal({
                    type: 'success',
                    title: 'Update User',
                    text: 'Anda Berhasil Mengubah Data User',
                  })
                  // tutup form pada modal
                  $('#edit_user').modal('hide');
              }
            })
            return false;
          });
      }
    });
  });
  
  $('#manajemen_user').on('click','.reset-pw-user', function () {
    var id_user =  $(this).data('id_user');
    swal({
        title: 'Konfirmasi',
        text: "Anda ingin reset password",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reset',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Tidak',
        reverseButtons: true
  
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url:'user/reset_password',
            method:"POST",
            beforeSend :function () {
            swal({
                title: 'Menunggu',
                html: 'Memproses data',
                onOpen: () => {
                  swal.showLoading()
                }
              })
            },
            data:{id_user:id_user},
            dataType: "JSON",
            success:function(data){
              swal(
                'Hapus',
                'Password Berhasil di Reset',
                'success'
              )
              user.ajax.reload(null, false)
            }
          })
      } else if (result.dismiss === swal.DismissReason.cancel) {
          swal(
            'Batal',
            'Anda membatalkan reset password',
            'error'
          )
        }
      })
    });
  
    $('#manajemen_user').on('click','.non-aktif-user', function () {
      var id_user =  $(this).data('id_user');
      swal({
          title: 'Konfirmasi',
          text: "Anda ingin Non-Aktifkan User ini?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Non-Aktif',
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          cancelButtonText: 'Tidak',
          reverseButtons: true
  
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:'user/non_aktif_user',
              method:"POST",
              beforeSend :function () {
              swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  onOpen: () => {
                    swal.showLoading()
                  }
                })
              },
              data:{id_user:id_user},
              dataType: "JSON",
              success:function(data){
                swal(
                  'Non-Aktif User',
                  'User berhasil di Non-Aktifkan',
                  'success'
                )
                user.ajax.reload(null, false)
              }
            })
        } else if (result.dismiss === swal.DismissReason.cancel) {
            swal(
              'Batal',
              'Anda membatalkan Non-Aktif User',
              'error'
            )
          }
        })
      });
  
      $('#manajemen_user').on('click','.aktif-user', function () {
        var id_user =  $(this).data('id_user');
        swal({
            title: 'Konfirmasi',
            text: "Anda ingin Aktifkan User ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Aktifkan',
            confirmButtonColor: '#00a65a',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            reverseButtons: true
  
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url:'user/aktif_user',
                method:"POST",
                beforeSend :function () {
                swal({
                    title: 'Menunggu',
                    html: 'Memproses data',
                    onOpen: () => {
                      swal.showLoading()
                    }
                  })
                },
                data:{id_user:id_user},
                dataType: "JSON",
                success:function(data){
                  swal(
                    'Non-Aktif User',
                    'User berhasil di Aktifkan',
                    'success'
                  )
                  user.ajax.reload(null, false)
                }
              })
          } else if (result.dismiss === swal.DismissReason.cancel) {
              swal(
                'Batal',
                'Anda membatalkan Aktifkan User',
                'error'
              )
            }
          })
        });
  });
  
  //manajemen_jurusan
  $(document).ready(function () {
    var prodi= $('#manajemen_prodi').DataTable({
      "processing": true,
      "autoWidth"   : true,
      "ajax": 'prodi/data_prodi',
      stateSave: true,
      "order": []
    })

    $('#tambah_prodi').on('submit', function(){
      var fakultas_id = $('#fakultas_id').val();
      var strata_id = $('#strata_id').val();
      var program_studi = $('#program_studi').val();
      var nama_kaprodi = $('#nama_kaprodi').val();
      var nip_kaprodi = $('#nip_kaprodi').val();
      var mulai_jabatan = $('#mulai_jabatan').val();
      var selesai_jabatan = $('#selesai_jabatan').val();
  
      $.ajax({
        type: "POST",
        url: 'prodi/tambah_prodi',
        beforeSend :function() {
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          fakultas_id:fakultas_id,
          strata_id:strata_id,
          program_studi:program_studi,
          nama_kaprodi:nama_kaprodi,
          nip_kaprodi:nip_kaprodi,
          mulai_jabatan:mulai_jabatan,
          selesai_jabatan:selesai_jabatan
        },
        dataType: "JSON",
        success: function (data) {
          prodi.ajax.reload(null, false);
          swal({
            type: "success",
            title: 'Tambah Fakultas',
            text: 'Anda berhasil menambah prodi',
          })
  
          $('#tambah-prodi').modal('hide');
  
          $('#fakultas_id').val('');
          $('#strata_id').val('');
          $('#program_studi').val('');
          $('#nama_kapordi').val('');
          $('#nip_kaprodi').val('');
          $('#mulai_jabatan').val('');
          $('#selesai_jabatan').val('');
        }
      })
      return false;
    });

    $('#manajemen_prodi').on('click', '.ubah-prodi', function(){
      var id_prodi = $(this).data('id_prodi');
  
      $.ajax({
        type: "POST",
        url: 'prodi/edit_prodi',
        beforSend: function(){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          id_prodi:id_prodi
        },
        success: function (data) {
          swal.close();
          $('#ubah_prodi').modal('show');
          $('#form_edit_prodi').html(data);

          $('#editprodi').on('submit', function(){
            var edit_program_studi = $('#edit_program_studi').val();

            var id_prodi= $('#idprodi').val();
            $.ajax({
              type: "POST",
              url: 'prodi/update_prodi',
              beforeSend: function(){
                swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  text: 'Proses Update Dataa',
                  buttons: false,
                  onOpen: () => {
                    swal.showLoading()
                  },
                })
              },
              
              data: {
                edit_program_studi:edit_program_studi,
               
                id_prodi:id_prodi
              },
              dataType: "JSON",
              success: function (data) {
                prodi.ajax.reload(null, false);
                swal({
                  type: "success",
                  title: 'Ubah Prodi',
                  text: 'Anda berhasil mengubah Prodi',
                })
        
                $('#ubah_prodi').modal('hide');

                if(data.success == true){ // if true (1)
                  setTimeout(function(){// wait for 5 secs(2)
                       location.reload(); // then reload the page.(3)
                  }, 5000); 
               }
                
              } 
            })
            return false;
          });
        }
      });
    });

    $('#manajemen_prodi').on('click', '.ganti-kaprodi', function(){
      var id_prodi = $(this).data('id_prodi');
  
      $.ajax({
        type: "POST",
        url: 'prodi/ganti_kaprodi',
        beforSend: function(){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          id_prodi:id_prodi
        },
        success: function (data) {
          swal.close();
          $('#ganti_kaprodi').modal('show');
          $('#form_ganti_kaprodi').html(data);

          $('#new_kaprodi').on('submit', function(){
            var prodi_id = $('#prodi_id').val();

            var new_nama_kaprodi = $('#new_nama_kaprodi').val();
            var new_nip_kaprodi = $('#new_nip_kaprodi').val();
            var new_mulai_jabatan = $('#new_mulai_jabatan').val();
            var new_selesai_jabatan = $('#new_selesai_jabatan').val();

            $.ajax({
              type: "POST",
              url: 'prodi/new_kaprodi',
              beforeSend: function(){
                swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  text: 'Proses Update Dataa',
                  buttons: false,
                  onOpen: () => {
                    swal.showLoading()
                  },
                })
              },
              
              data: {
                new_nama_kaprodi:new_nama_kaprodi,
                new_nip_kaprodi:new_nip_kaprodi,
                new_mulai_jabatan:new_mulai_jabatan,
                new_selesai_jabatan:new_selesai_jabatan,

                prodi_id:prodi_id
              },
              dataType: "JSON",
              success: function (data) {
                prodi.ajax.reload(null, false);
                swal({
                  type: "success",
                  title: 'Ganti Kapordi',
                  text: 'Anda berhasil mengganti kaprodi',
                })
        
                $('#ganti_kaprodi').modal('hide');
                
              } 
            })
            return false;
          });
        }
      });
    });

    $('#manajemen_prodi').on('click','.detail-prodi', function () {
      var id_prodi = $(this).data('id_prodi');
  
      $.ajax({
        type: "POST",
        url: 'prodi/detail_prodi',
        beforSend: function (){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {id_prodi:id_prodi},
        success: function (data){
          swal.close();
            $('#detail_prodi').modal('show');
            $('#tampil_prodi').html(data);
        }
      })
      return false;
    });

    $('#manajemen_prodi').on('click', '.hapus-prodi', function(){
      var id_prodi = $(this).data('id_prodi');
      swal({
        title: 'Konfirmasi',
        text: "Anda ingin hapus prodi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Tidak',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: 'prodi/delete_prodi',
            method: "POST",
            beforSend: function() {
              swal({
                title: 'Menunggu',
                html: 'Memproses data',
                onOpen: () => {
                  swal.showLoading()
                }
              })
            },
            data:{id_prodi:id_prodi},
            dataType: "JSON",
            success: function(data){
              swal(
                'Hapus',
                'Fakultas berhasil di prodi',
                'success'
              )
              prodi.ajax.reload(null, false)
            }
          })
        } else if (result.dismiss === swal.DismissReason.cancel) {
            swal(
              'Batal',
              'Anda membatalkan menghapaus data',
              'error'
            )
          }
        })
    });

      
  });
  
  //manajemen_fakultas
  $(document).ready(function () {
    var fakultas = $('#manajemen_fakultas').DataTable({
        "processing": true,
        "ajax": 'fakultas/data_fakultas',
        stateSave: true,
        "order": []
    })

    $('#tambah_fakultas').on('submit', function(){
      var nama_fakultas = $('#nama_fakultas').val();
      var singkatan_fakultas = $('#singkatan_fakultas').val();
      var nama_wadek_satu = $('#nama_wadek_satu').val();
      var nip_wadek_satu = $('#nip_wadek_satu').val();
      var mulai_jabatan = $('#mulai_jabatan').val();
      var selesai_jabatan = $('#selesai_jabatan').val();
  
      $.ajax({
        type: "POST",
        url: 'fakultas/tambah_fakultas',
        beforeSend :function() {
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          nama_fakultas:nama_fakultas,
          singkatan_fakultas:singkatan_fakultas,
          nama_wadek_satu:nama_wadek_satu,
          nip_wadek_satu:nip_wadek_satu,
          mulai_jabatan:mulai_jabatan,
          selesai_jabatan:selesai_jabatan
        },
        dataType: "JSON",
        success: function (data) {
          fakultas.ajax.reload(null, false);
          swal({
            type: "success",
            title: 'Tambah Fakultas',
            text: 'Anda berhasil menambah fakultas',
          })
  
          $('#tambah-fakultas').modal('hide');
  
          $('#nama_fakultas').val('');
          $('#singkatan_fakultas').val('');
          $('#wadek_satu').val('');
          $('#nip_wadek_satu').val('');
          $('#mulai_jabatan').val('');
          $('#selesai_jabatan').val('');
        }
      })
      return false;
    });

    $('#manajemen_fakultas').on('click', '.ubah-fakultas', function(){
      var id_fakultas = $(this).data('id_fakultas');
  
      $.ajax({
        type: "POST",
        url: 'fakultas/edit_fakultas',
        beforSend: function(){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          id_fakultas:id_fakultas
        },
        success: function (data) {
          swal.close();
          $('#ubah_fakultas').modal('show');
          $('#form_edit_fakultas').html(data);

          $('#editfakultas').on('submit', function(){
            var edit_nama_fakultas = $('#edit_nama_fakultas').val();
            var edit_singkatan_fakultas = $('#edit_singkatan_fakultas').val();

            var id_fakultas = $('#idfakultas').val();
            $.ajax({
              type: "POST",
              url: 'fakultas/update_fakultas',
              beforeSend: function(){
                swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  text: 'Proses Update Dataa',
                  buttons: false,
                  onOpen: () => {
                    swal.showLoading()
                  },
                })
              },
              
              data: {
                edit_nama_fakultas:edit_nama_fakultas,
                edit_singkatan_fakultas:edit_singkatan_fakultas,
                id_fakultas:id_fakultas
              },
              dataType: "JSON",
              success: function (data) {
                fakultas.ajax.reload(null, false);
                swal({
                  type: "success",
                  title: 'Ubah Fakultas',
                  text: 'Anda berhasil mengubah fakultas',
                })
        
                $('#ubah_fakultas').modal('hide');

                if(data.success == true){ // if true (1)
                  setTimeout(function(){// wait for 5 secs(2)
                       location.reload(); // then reload the page.(3)
                  }, 5000); 
               }
                
              } 
            })
            return false;
          });
        }
      });
    });

    $('#manajemen_fakultas').on('click', '.ganti-wadek', function(){
      var id_fakultas = $(this).data('id_fakultas');
  
      $.ajax({
        type: "POST",
        url: 'fakultas/ganti_wadek',
        beforSend: function(){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {
          id_fakultas:id_fakultas
        },
        success: function (data) {
          swal.close();
          $('#ganti_wadek').modal('show');
          $('#form_ganti_wadek').html(data);

          $('#new_wadek').on('submit', function(){
            var fakultas_id = $('#fakultas_id').val();

            var new_nama_wadek_satu = $('#new_nama_wadek_satu').val();
            var new_nip_wadek_satu = $('#new_nip_wadek_satu').val();
            var new_mulai_jabatan = $('#new_mulai_jabatan').val();
            var new_selesai_jabatan = $('#new_selesai_jabatan').val();

            $.ajax({
              type: "POST",
              url: 'fakultas/new_wadek',
              beforeSend: function(){
                swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  text: 'Proses Update Dataa',
                  buttons: false,
                  onOpen: () => {
                    swal.showLoading()
                  },
                })
              },
              
              data: {
                new_nama_wadek_satu:new_nama_wadek_satu,
                new_nip_wadek_satu:new_nip_wadek_satu,
                new_mulai_jabatan:new_mulai_jabatan,
                new_selesai_jabatan:new_selesai_jabatan,

                fakultas_id:fakultas_id
              },
              dataType: "JSON",
              success: function (data) {
                fakultas.ajax.reload(null, false);
                swal({
                  type: "success",
                  title: 'Ganti Dekan',
                  text: 'Anda berhasil mengganti dekan',
                })
        
                $('#ganti_wadek').modal('hide');
                
              } 
            })
            return false;
          });
        }
      });
    });

    $('#manajemen_fakultas').on('click','.detail-fakultas', function () {
      var id_fakultas = $(this).data('id_fakultas');
  
      $.ajax({
        type: "POST",
        url: 'fakultas/detail_fakultas',
        beforSend: function (){
          let timerInterval
          swal({
            title: 'Menunggu',
            html: 'Memproses data',
            text: 'Proses Data',
            timer: 2000,
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        data: {id_fakultas:id_fakultas},
        success: function (data){
          swal.close();
            $('#detail_fakultas').modal('show');
            $('#tampil_fakultas').html(data);
        }
      })
      return false;
    });

    $('#manajemen_fakultas').on('click', '.hapus-fakultas', function(){
      var id_fakultas = $(this).data('id_fakultas');
      swal({
        title: 'Konfirmasi',
        text: "Anda ingin hapus fakultas",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Tidak',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: 'fakultas/delete_fakultas',
            method: "POST",
            beforSend: function() {
              swal({
                title: 'Menunggu',
                html: 'Memproses data',
                onOpen: () => {
                  swal.showLoading()
                }
              })
            },
            data:{id_fakultas:id_fakultas},
            dataType: "JSON",
            success: function(data){
              swal(
                'Hapus',
                'Fakultas berhasil di hapus',
                'success'
              )
              fakultas.ajax.reload(null, false)
            }
          })
        } else if (result.dismiss === swal.DismissReason.cancel) {
            swal(
              'Batal',
              'Anda membatalkan menghapaus data',
              'error'
            )
          }
        })
    });

  });
  
  
  
  $("#select_fakultas").hide();
  $("#select_jurusan").hide();
  $("#select_lab").hide();
  $("#select_unit").hide();
  setSelector("#role");
  $("#role").change(function(){
    setSelector(this);
  });
  
  function setSelector(select){
    if($(select).val() == '7'){
      $("#select_fakultas").show();
      $("#select_jurusan").hide();
      $("#select_lab").hide();
      $("#select_unit").hide();
    }
    else{
      if($(select).val() == '2'){
        $("#select_fakultas").hide();
        $("#select_jurusan").show();
        $("#select_lab").hide();
        $("#select_unit").hide();
      }
      else{
        if($(select).val() == '6'){
          $("#select_fakultas").hide();
          $("#select_jurusan").hide();
          $("#select_lab").hide();
          $("#select_unit").show();
        }else{
          if($(select).val() == '5'){
            $("#select_fakultas").hide();
            $("#select_jurusan").hide();
            $("#select_lab").show();
            $("#select_unit").hide();
          }else{
            $("#select_fakultas").hide();
            $("#select_jurusan").hide();
            $("#select_lab").hide();
            $("#select_unit").hide();
          }
        }
      }
    }
  }

  $(document).ready(function() {

    $('.instrumen_matkul').footable({
      paginate:false,
      columns : [
        { width : '2px' },
        { width : '100px' },
        { width : '50px' },
        { width : '50px' }       
    ], 
    });
  });

function getFormData($form){
  var unindexed_array = $form.serializeArray();
  var indexed_array = {};
    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

$(document).ready(function() { 
      $('.gambar').each(function(){
          var url = $(this).attr("src");
          $(this).zoom({url: url});
      });


    buka(1);

    widget      = $(".step");
    btnnext     = $(".next");
    btnback     = $(".back"); 
    btnsubmit   = $(".submit");

    $(".step").hide();
    $(".back").hide();
    $("#widget_1").show();
});
  
widget      = $(".step");
total_widget = widget.length;

next = function() {
    var berikutnya  = $(".next").attr('rel');
    berikutnya = parseInt(berikutnya);
    berikutnya = berikutnya > total_widget ? total_widget : berikutnya;

    $("#soalke").html(berikutnya);

    $(".next").attr('rel', (berikutnya+1));
    $(".back").attr('rel', (berikutnya-1));
    
    var sudah_akhir = berikutnya == total_widget ? 1 : 0;
    
    
    $(".step").hide();
    $("#widget_"+berikutnya).show();


    if (sudah_akhir == 1) {
        $(".back").show();
        $(".next").hide();
    } else if (sudah_akhir == 0) {
        $(".next").show();
        $(".back").show();
    }
}

back = function() {
    var back  = $(".back").attr('rel');
    back = parseInt(back);
    back = back < 1 ? 1 : back;

    $("#soalke").html(back);
    
    $(".back").attr('rel', (back-1));
    $(".next").attr('rel', (back+1));
    
    $(".step").hide();
    $("#widget_"+back).show();

    var sudah_awal = back == 1 ? 1 : 0;
     
    $(".step").hide();
    $("#widget_"+back).show();
    
    if (sudah_awal == 1) {
        $(".back").hide();
        $(".next").show();
    } else if (sudah_awal == 0) {
        $(".next").show();
        $(".back").show();
    }
}

buka = function(id_widget) {
    $(".next").attr('rel', (id_widget+1));
    $(".back").attr('rel', (id_widget-1));

    $("#borangke").html(id_widget);
    
    $(".step").hide();
    $("#widget_"+id_widget).show();
} 
 
// semoga cukup samapai disini :v 
// mau 2rb?
// Artinya kode wanita,
// lebih sulit walau cuma 1 :D